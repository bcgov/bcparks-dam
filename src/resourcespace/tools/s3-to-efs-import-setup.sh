sudo tee /usr/local/sbin/s3-to-efs-import.sh >/dev/null <<'EOF'
#!/usr/bin/env bash
set -euo pipefail

# =========================
# s3-to-efs-import.sh
# One-command setup + run:
# - Prompts for env/email (or accepts switches)
# - Installs a runner script used by systemd
# - Creates/enables/starts systemd service
# - Creates SNS topic + email subscription
# - Creates CloudWatch alarm for missing heartbeat
# - Runs S3->EFS sync + verify (dry-run must be empty)
# =========================

usage() {
  cat <<USAGE
Usage:
  sudo s3-to-efs-import.sh [-n dev|test|prod] [-e email] [-d dest_path] [-b s3_bucket] [-p s3_prefix] [--no-prompt] [--delete]

Examples:
  sudo s3-to-efs-import.sh
  sudo s3-to-efs-import.sh -n dev -e you@example.com
  sudo s3-to-efs-import.sh -n prod -e you@example.com -b s3://my-prod-bucket -p filestore

Options:
  -n   Environment (dev/test/prod). If omitted, prompts.
  -e   Email for SNS alerts. If omitted, prompts.
  -d   Destination path (EFS-backed filestore). Default: /var/www/resourcespace/filestore
  -b   Source S3 bucket URL (e.g., s3://bucket-name). If omitted, uses env default (dev only) or prompts.
  -p   Source prefix inside bucket. Default: filestore
  --delete     Mirror mode: delete files in DEST not present in S3 (DANGEROUS; use with care)
  --no-prompt  Fail instead of prompting if required values missing.
USAGE
  exit 1
}

DELETE_MODE="false"
NO_PROMPT="false"

# handle long flags
for arg in "$@"; do
  case "$arg" in
    --delete) DELETE_MODE="true" ;;
    --no-prompt) NO_PROMPT="true" ;;
  esac
done

ENV_NAME=""
ALERT_EMAIL=""
DEST_PATH="/var/www/resourcespace/filestore"
SRC_BUCKET=""
SRC_PREFIX="filestore"

# parse short flags
while getopts ":n:e:d:b:p:h" opt; do
  case "$opt" in
    n) ENV_NAME="$OPTARG" ;;
    e) ALERT_EMAIL="$OPTARG" ;;
    d) DEST_PATH="$OPTARG" ;;
    b) SRC_BUCKET="$OPTARG" ;;
    p) SRC_PREFIX="$OPTARG" ;;
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
#   Import bucket in dev: s3://bcparks-dam-backup-dev/
default_bucket_for_env() {
  echo "s3://bcparks-dam-backup-${ENV_NAME}"
}

ensure_bucket() {
  if [[ -z "$SRC_BUCKET" ]]; then
    SRC_BUCKET="$(default_bucket_for_env)"
  fi
  if [[ -z "$SRC_BUCKET" ]]; then
    prompt SRC_BUCKET "Source S3 bucket (e.g., s3://my-bucket): "
  fi
  SRC_BUCKET="${SRC_BUCKET%/}"
}

# ---- AWS/CloudWatch/SNS config ----
CW_NAMESPACE="BCParks DAM"
METRIC_HEARTBEAT="EfsImportHeartbeat"
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
RUNNER="/usr/local/sbin/s3-to-efs-import-runner.sh"
LOG="/var/log/s3-to-efs-import-${ENV_NAME}.log"
LOCK="/var/lock/s3-to-efs-import-${ENV_NAME}.lock"
SERVICE="s3-to-efs-import-${ENV_NAME}.service"

# ---- exclude volatile paths (ResourceSpace) ----
EXCLUDES=( '--exclude "tmp/*"' )

write_runner() {
  sudo tee "$RUNNER" >/dev/null <<RUNEOF
#!/usr/bin/env bash
set -euo pipefail

SRC_BUCKET="${SRC_BUCKET}"
SRC_PREFIX="${SRC_PREFIX}"
SRC="\${SRC_BUCKET}/\${SRC_PREFIX}"

DEST_PATH="${DEST_PATH}"

LOG="${LOG}"
LOCK="${LOCK}"

CW_NAMESPACE="${CW_NAMESPACE}"
METRIC_HEARTBEAT="${METRIC_HEARTBEAT}"
JOB_NAME="${JOB_NAME}"
HEARTBEAT_PERIOD_SEC=${PERIOD_SEC}
INSTANCE_ID="${INSTANCE_ID}"
DELETE_MODE="${DELETE_MODE}"

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

  exec 9>"\$LOCK"
  if ! flock -n 9; then
    log "Another import is already running (lock: \$LOCK). Exiting."
    exit 0
  fi

  mkdir -p "\$DEST_PATH"

  log "=== START S3→EFS import ==="
  log "SRC=\$SRC"
  log "DEST=\$DEST_PATH"
  log "DELETE_MODE=\$DELETE_MODE"

  heartbeat_loop &
  HB_PID=\$!
  trap 'kill "\$HB_PID" 2>/dev/null || true' EXIT

  put_metric "EfsImportStarted" 1 || true

  SYNC_OPTS=( --exact-timestamps --only-show-errors "\${EXCLUDES[@]}" )
  if [[ "\$DELETE_MODE" == "true" ]]; then
    SYNC_OPTS+=( --delete )
  fi

  log "--- Running import: aws s3 sync ---"
  aws s3 sync "\$SRC/" "\$DEST_PATH/" "\${SYNC_OPTS[@]}"

  log "--- Verifying (dry-run): aws s3 sync --dryrun ---"
  #DRYRUN_OUT="\$(aws s3 sync "\$SRC/" "\$DEST_PATH/" --exact-timestamps --dryrun "\${EXCLUDES[@]}" || true)"
  DRYRUN_OUT="\$(aws s3 sync "\$SRC/" "\$DEST_PATH/" --exact-timestamps --dryrun --exclude 'tmp/*' || true)"
  if [[ -n "\$DRYRUN_OUT" ]]; then
    log "VERIFY FAILED: dry-run found differences:"
    echo "\$DRYRUN_OUT"
    put_metric "EfsImportFailed" 1 || true
    exit 2
  fi

  log "VERIFY OK: no differences detected by dry-run."
  put_metric "EfsImportSucceeded" 1 || true
  log "=== SUCCESS S3→EFS import completed ==="
}

main "\$@"
RUNEOF
  sudo chmod 0755 "$RUNNER"
}

write_service() {
  sudo tee "/etc/systemd/system/${SERVICE}" >/dev/null <<UNITEOF
[Unit]
Description=S3 to EFS Import (${ENV_NAME}) - reliable, resumable
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
  SNS_TOPIC_NAME="efs-import-alerts-${ENV_NAME}"
  ALARM_NAME="efs-import-heartbeat-missing-${ENV_NAME}-${INSTANCE_ID}"

  local topic_arn
  topic_arn="$(aws sns create-topic --name "$SNS_TOPIC_NAME" --query TopicArn --output text)"
  echo "SNS topic ARN: $topic_arn"

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

  aws cloudwatch put-metric-alarm \
    --alarm-name "$ALARM_NAME" \
    --alarm-description "S3→EFS import heartbeat missing (${ENV_NAME})" \
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

  JOB_NAME="efs-${ENV_NAME}-import"

  LOG="/var/log/s3-to-efs-import-${ENV_NAME}.log"
  LOCK="/var/lock/s3-to-efs-import-${ENV_NAME}.lock"
  SERVICE="s3-to-efs-import-${ENV_NAME}.service"

  echo
  echo "=== s3-to-efs-import setup ==="
  echo "Env:        $ENV_NAME"
  echo "Account:    $ACCOUNT_ID"
  echo "Region:     $REGION"
  echo "Instance:   $INSTANCE_ID"
  echo "Email:      $ALERT_EMAIL"
  echo "Source:     ${SRC_BUCKET}/${SRC_PREFIX}"
  echo "Dest path:  $DEST_PATH"
  echo "JobName:    $JOB_NAME"
  echo "Delete:     $DELETE_MODE"
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

sudo chmod 0755 /usr/local/sbin/s3-to-efs-import.sh
echo "s3-to-efs-import.sh installed. Run with sudo /usr/local/sbin/s3-to-efs-import.sh"