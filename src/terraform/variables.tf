# variables.tf

variable "app_name" {
  description = "BC Parks DAM"
  type        = string
  default     = "bcparks-dam"
}

variable "image_id" {
  description = "id of the AWS Marketplace AMI (Amazon Machine Image) for Bitnami ResourceSpace"
  default     = "ami-0adad14dcb2ca073f" 
  type        = string
}
#ami-0adad14dcb2ca073f  Debian 12
# Bitnami AMIs:
  #ami-0e3cecd2b3d50ee5f  10.3.0-1-r02 on Debian 11 | current version on prod
  #ami-05ffc9127116f1111  10.3.0-4-r164 on Debian 12
  #ami-0e591624006f49399  10.3.0-5-r165 on Debian 12
  #ami-0023310960cb82ef6  10.3.0-5-r166 on Debian 12 | current version on test

variable "target_env" {
  description = "AWS workload account env (e.g. dev, test, prod, sandbox, unclass)"
}

variable "branch_name" {
  description = "The name of the branch"
  type        = string
}

variable "git_url" {
  description = "url of the git repo to clone the ansible files"
  default     = "https://github.com/bcgov/bcparks-dam.git"
  type        = string
}

variable "lc_name" {
  description = "Name of the launch configuration"
  default     = "dam-vm-lc"
  type        = string
}

variable "asg_name" {
  description = "name of the autoscaling group created"
  default     = "dam-vm-asg"
  type        = string
}

variable "app_port" {
  description = "Port exposed by the VM image to redirect traffic to"
  default     = 80
}

variable "health_check_path" {
  default = "/health"
}

variable "common_tags" {
  description = "Common tags for created resources"
  default = {
    Application = "BCParks DAM"
  }
}

variable "aws_region" {
  description = "region of the aws"
  default     = "ca-central-1"
  type        = string
}

variable "service_names" {
  description = "List of service names to use as subdomains"
  default     = ["dam"]
  type        = list(string)
}

variable "alb_name" {
  description = "Name of the internal alb"
  default     = "default"
  type        = string
}

variable "domain_name" {
  description = "The domain name for the application"
  default     = "dam.lqc63d-dev.nimbus.cloud.gov.bc.ca"
  type        = string
}

variable "licence_plate" {
  description = "The licence plate for the application"
  default     = "[LICENCEPLATE]"
  type        = string
}