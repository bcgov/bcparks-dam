# BC Parks DAM Technical Architecture Summary

## Overview

The BC Parks Digital Asset Management (DAM) solution is a private AWS-hosted ResourceSpace deployment designed for secure document and media management in the BC Government environment. It is provisioned through Terraform and Terragrunt and is separated by environment into dev, test, and prod.

## Core architecture

- ResourceSpace runs on a Debian-based EC2 instance behind an internal Application Load Balancer.
- The ALB receives HTTPS traffic on port 443 and forwards to the app instance on port 80.
- The instance is managed by an Auto Scaling Group with a single active node for the current deployment pattern.
- Aurora MySQL stores application metadata and operational data.
- Amazon EFS provides the persistent filestore for uploaded files and media.
- An S3 bucket is mounted for backup and file-transfer workflows.
- Application secrets and SAML configuration are injected from AWS Secrets Manager during instance bootstrapping.

## Why this design fits the workload

This architecture keeps the DAM application in a private, managed AWS environment with strong network isolation and reduced exposure. It separates transactional data from file storage, supports operational resilience through persistent stores, and keeps deployment consistent across environments through infrastructure-as-code.

## Operational model

The EC2 instance is bootstrapped by a user-data script that installs NGINX, PHP-FPM, ResourceSpace, media tools, and supporting services. The script also mounts EFS and the S3 backup volume, configures ResourceSpace, and schedules cron jobs for offline processing and cleanup.

## Security and governance

- Private-only ALB and database access
- Security groups mapped to web, app, and data tiers
- Storage encryption enabled for Aurora
- Secret values kept outside source control
- ACM certificate termination at the ALB

## Summary

The solution is a conservative, secure, and maintainable DAM deployment that aligns with the BC Government hosting model: private networking, managed data services, persistent asset storage, and automated infrastructure provisioning.
