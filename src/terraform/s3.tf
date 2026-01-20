# s3.tf

resource "aws_s3_bucket" "s3_bucket" {
  bucket = "bcparks-dam-backup-${var.target_env}"
  tags   = var.common_tags
}