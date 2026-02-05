sudo tee /usr/local/sbin/efs-to-s3-export.sh >/dev/null <<'EOF'
#!/usr/bin/env bash
set -euo pipefail

# =========================
# efs-to-s3-export.sh
# One-command setup + run:
# - Prompts for env/email (or accepts switches)
# - Installs a runner script used by systemd
# - Creates/enables/starts systemd service
# - Creates SNS topic + email subscription
# - Creates CloudWatch alarm for missing heartbeat
# - Runs EFS->S3 sync + verify (dry-run must be empty)
# =========================

usage() {
  cat <<USAGE
Usage:
  sudo efs-to-s3-export.sh [-n dev|test|prod] [-e email] [-s source_path] [-b s3_bucket] [-p s3_prefix] [--no-prompt]

Examples:
  sudo efs-to-s3-export.sh
  sudo efs-to-s3-export.sh -n dev -e you@example.com
  sudo efs-to-s3-export.sh -n test -e you@example.com -b s3://my-test-bucket -p filestore

Options:
  -n   Environment (dev/test/prod). If omitted, prompts.
  -e   Email for SNS alerts. If omitted, prompts.
  -s   Source path (EFS-mounted filestore). Default: /var/www/resourcespace/filestore
  -b   Destination S3 bucket URL (e.g., s3://bucket-name). If omitted, uses env default (dev only) or prompts.
  -p   Destination prefix inside bucket. Default: filestore
  --no-prompt  Fail instead of prompting if required values missing.
USAGE
  exit 1
}

NO_PROMPT="false"
if [[ "${1:-}" == "--no-prompt" ]]; then NO_PROMPT="true"; shift; fi

ENV_NAME=""
ALERT_EMAIL=""
SRC_PATH="/var/www/resourcespace/filestore"
DEST_BUCKET=""
DEST_PREFIX="filestore"

while getopts ":n:e:s:b:p:h" opt; do
  case "$opt" in
    n) ENV_NAME="$OPTARG" ;;
    e) ALERT_EMAIL="$OPTARG" ;;
    s) SRC_PATH="$OPTARG" ;;
    b) DEST_BUCKET="$OPTARG" ;;
    p) DEST_PREFIX="$OPTARG" ;;
    h) usage ;;
    *) usage ;;
  esac
done

prompt() {
  local varname="$1" prompt_text="$2"
  if [[ "$NO_PROMPT" == "true" ]]; then
    echo "ERROR: Missing required value: $varname (use flags or omit --no-prompt)" >&2
    exit 2
  fi
  read -r -p "$prompt_text" val
  printf -v "$varname" "%s" "$val"
}

normalize_env() {
  if [[ -z "$ENV_NAME" ]]; then prompt ENV_NAME "Environment (dev/test/prod): "; fi
  ENV_NAME="$(echo "$ENV_NAME" | tr '[:upper:]' '[:lower:]')"
  case "$ENV_NAME" in dev|test|prod) ;; *) echo "ERROR: env must be dev/test/prod" >&2; exit 2;; esac
}

ensure_email() {
  if [[ -z "$ALERT_EMAIL" ]]; then prompt ALERT_EMAIL "Alert email address: "; fi
}

# Dev defaults provided by you:
#   Export bucket in dev: s3://bcparks-dam-dev-backup/
default_bucket_for_env() {
  echo "s3://bcparks-dam-${ENV_NAME}-backup"
}

ensure_bucket() {
  if [[ -z "$DEST_BUCKET" ]]; then
    DEST_BUCKET="$(default_bucket_for_env)"
  fi
  if [[ -z "$DEST_BUCKET" ]]; then
    prompt DEST_BUCKET "Destination S3 bucket (e.g., s3://my-bucket): "
  fi
  # strip trailing slash if present
  DEST_BUCKET="${DEST_BUCKET%/}"
}

# ---- AWS/CloudWatch/SNS config ----
CW_NAMESPACE="BCParks DAM"                    # spaces are OK; change if you want
METRIC_HEARTBEAT="EfsExportHeartbeat"
JOB_NAME=""

SNS_TOPIC_NAME=""
ALARM_NAME=""

PERIOD_SEC=300
EVAL_PERIODS=2
DATAPOINTS_TO_ALARM=2
THRESHOLD=1

#INSTANCE_ID="$(curl -sS http://169.254.169.254/latest/meta-data/instance-id || echo unknown)"
TOKEN="$(curl -sS -X PUT "http://169.254.169.254/latest/api/token" \
  -H "X-aws-ec2-metadata-token-ttl-seconds: 21600" || true)"
INSTANCE_ID="$(curl -sS -H "X-aws-ec2-metadata-token: ${TOKEN}" \
  http://169.254.169.254/latest/meta-data/instance-id || true)"

if [[ -z "$INSTANCE_ID" ]]; then
  # fallback: try AWS CLI (works in most SSM contexts if ec2:DescribeInstances allowed)
  INSTANCE_ID="$(aws ec2 describe-instances \
    --filters "Name=private-dns-name,Values=$(hostname -f)" \
    --query "Reservations[0].Instances[0].InstanceId" --output text 2>/dev/null || true)"
fi

if [[ -z "$INSTANCE_ID" ]]; then
  echo "ERROR: Could not determine InstanceId (needed for CloudWatch alarm dimensions)." >&2
  echo "Fix: ensure IMDS is enabled/reachable, or grant ec2:DescribeInstances to the role." >&2
  exit 2
fi

ACCOUNT_ID="$(aws sts get-caller-identity --query Account --output text 2>/dev/null || echo unknown)"
REGION="${AWS_REGION:-$(aws configure get region || true)}"
REGION="${REGION:-ca-central-1}"

# ---- Paths / filenames ----
RUNNER="/usr/local/sbin/efs-to-s3-export-runner.sh"
LOG="/var/log/efs-to-s3-export-${ENV_NAME}.log"
LOCK="/var/lock/efs-to-s3-export-${ENV_NAME}.lock"
SERVICE="efs-to-s3-export-${ENV_NAME}.service"

# ---- exclude volatile paths (ResourceSpace) ----
EXCLUDES=( '--exclude "tmp/*"' )

write_runner() {
  sudo tee "$RUNNER" >/dev/null <<RUNEOF
#!/usr/bin/env bash
set -euo pipefail

SRC_PATH="${SRC_PATH}"
DEST_BUCKET="${DEST_BUCKET}"
DEST_PREFIX="${DEST_PREFIX}"
DEST="\${DEST_BUCKET}/\${DEST_PREFIX}"

LOG="${LOG}"
LOCK="${LOCK}"

CW_NAMESPACE="${CW_NAMESPACE}"
METRIC_HEARTBEAT="${METRIC_HEARTBEAT}"
JOB_NAME="${JOB_NAME}"
HEARTBEAT_PERIOD_SEC=${PERIOD_SEC}
INSTANCE_ID="${INSTANCE_ID}"

EXCLUDES=(${EXCLUDES[*]})

log() { echo "[\$(date -Is)] \$*" ; }

put_metric() {
  local metric="\$1" value="\$2"
  aws cloudwatch put-metric-data \
    --namespace "\$CW_NAMESPACE" \
    --metric-data "[
      {
        \\"MetricName\\": \\"\${metric}\\",
        \\"Dimensions\\": [
          {\\"Name\\": \\"InstanceId\\", \\"Value\\": \\"\${INSTANCE_ID}\\"},
          {\\"Name\\": \\"JobName\\", \\"Value\\": \\"\${JOB_NAME}\\"}
        ],
        \\"Value\\": \${value},
        \\"Unit\\": \\"Count\\"
      }
    ]" >/dev/null
}

heartbeat_loop() {
  while true; do
    put_metric "\$METRIC_HEARTBEAT" 1 || true
    sleep "\$HEARTBEAT_PERIOD_SEC" || true
  done
}

main() {
  mkdir -p "\$(dirname "\$LOG")"
  touch "\$LOG"
  chmod 640 "\$LOG"
  exec >>"\$LOG" 2>&1

  # lock prevents concurrent runs
  exec 9>"\$LOCK"
  if ! flock -n 9; then
    log "Another export is already running (lock: \$LOCK). Exiting."
    exit 0
  fi

  log "=== START EFS→S3 export ==="
  log "SRC=\$SRC_PATH"
  log "DEST=\$DEST"

  heartbeat_loop &
  HB_PID=\$!
  trap 'kill "\$HB_PID" 2>/dev/null || true' EXIT

  put_metric "EfsExportStarted" 1 || true

  log "--- Running copy: aws s3 sync ---"
  aws s3 sync "\$SRC_PATH" "\$DEST" \
    --exact-timestamps \
    --only-show-errors \
    "\${EXCLUDES[@]}"

  log "--- Verifying (dry-run): aws s3 sync --dryrun ---"
  DRYRUN_OUT="\$(aws s3 sync "\$SRC_PATH" "\$DEST" --exact-timestamps --dryrun "\${EXCLUDES[@]}" || true)"
  if [[ -n "\$DRYRUN_OUT" ]]; then
    log "VERIFY FAILED: dry-run found differences:"
    echo "\$DRYRUN_OUT"
    put_metric "EfsExportFailed" 1 || true
    exit 2
  fi

  log "VERIFY OK: no differences detected by dry-run."
  put_metric "EfsExportSucceeded" 1 || true
  log "=== SUCCESS EFS→S3 export completed ==="
}

main "\$@"
RUNEOF
  sudo chmod 0755 "$RUNNER"
}

write_service() {
  sudo tee "/etc/systemd/system/${SERVICE}" >/dev/null <<UNITEOF
[Unit]
Description=EFS to S3 Export (${ENV_NAME}) - reliable, resumable
Wants=network-online.target
After=network-online.target

[Service]
Type=simple
ExecStart=${RUNNER}
Restart=on-failure
RestartSec=30
Nice=10
TimeoutStartSec=infinity

[Install]
WantedBy=multi-user.target
UNITEOF

  sudo systemctl daemon-reload
  sudo systemctl enable "${SERVICE}" >/dev/null
}

setup_sns_and_alarm() {
  # env-specific names
  SNS_TOPIC_NAME="efs-export-alerts-${ENV_NAME}"
  ALARM_NAME="efs-export-heartbeat-missing-${ENV_NAME}-${INSTANCE_ID}"

  local topic_arn
  topic_arn="$(aws sns create-topic --name "$SNS_TOPIC_NAME" --query TopicArn --output text)"
  echo "SNS topic ARN: $topic_arn"

  # subscribe if not already subscribed
  local existing
  existing="$(aws sns list-subscriptions-by-topic --topic-arn "$topic_arn" \
    --query "Subscriptions[?Protocol=='email' && Endpoint=='${ALERT_EMAIL}'].SubscriptionArn | [0]" \
    --output text || true)"

  if [[ -z "$existing" || "$existing" == "None" ]]; then
    aws sns subscribe --topic-arn "$topic_arn" --protocol email --notification-endpoint "$ALERT_EMAIL" >/dev/null
    echo "Subscription requested for: $ALERT_EMAIL"
    echo "ACTION REQUIRED: Confirm the subscription from your email."
  else
    echo "Email already subscribed (or pending confirmation): $ALERT_EMAIL"
  fi

  # alarm on missing heartbeat
  aws cloudwatch put-metric-alarm \
    --alarm-name "$ALARM_NAME" \
    --alarm-description "EFS→S3 export heartbeat missing (${ENV_NAME})" \
    --namespace "$CW_NAMESPACE" \
    --metric-name "$METRIC_HEARTBEAT" \
    --dimensions "Name=InstanceId,Value=${INSTANCE_ID}" "Name=JobName,Value=${JOB_NAME}" \
    --statistic Sum \
    --period "$PERIOD_SEC" \
    --evaluation-periods "$EVAL_PERIODS" \
    --datapoints-to-alarm "$DATAPOINTS_TO_ALARM" \
    --threshold "$THRESHOLD" \
    --comparison-operator LessThanThreshold \
    --treat-missing-data breaching \
    --alarm-actions "$topic_arn"

  echo "CloudWatch alarm created/updated: $ALARM_NAME"
}

main() {
  normalize_env
  ensure_email
  ensure_bucket

  JOB_NAME="efs-${ENV_NAME}-export"

  # update derived paths now that ENV_NAME is known
  LOG="/var/log/efs-to-s3-export-${ENV_NAME}.log"
  LOCK="/var/lock/efs-to-s3-export-${ENV_NAME}.lock"
  SERVICE="efs-to-s3-export-${ENV_NAME}.service"

  echo
  echo "=== efs-to-s3-export setup ==="
  echo "Env:        $ENV_NAME"
  echo "Account:    $ACCOUNT_ID"
  echo "Region:     $REGION"
  echo "Instance:   $INSTANCE_ID"
  echo "Email:      $ALERT_EMAIL"
  echo "Source:     $SRC_PATH"
  echo "Dest bucket:$DEST_BUCKET"
  echo "Dest prefix:$DEST_PREFIX"
  echo "JobName:    $JOB_NAME"
  echo

  write_runner
  write_service
  setup_sns_and_alarm

  echo
  echo "Starting service: ${SERVICE}"
  sudo systemctl restart "${SERVICE}"
  sudo systemctl status "${SERVICE}" --no-pager || true
  echo
  echo "Log: $LOG"
  echo "Tail: sudo tail -f $LOG"
}

main "$@"
EOF

sudo chmod 0755 /usr/local/sbin/efs-to-s3-export.sh
echo "efs-to-s3-export.sh installed. Run with sudo /usr/local/sbin/efs-to-s3-export.sh"