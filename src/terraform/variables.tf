# variables.tf

variable "app_name" {
  description = "BC Parks DAM"
  type        = string
  default     = "bcparks-dam"
}

variable "image_id" {
  description = "id of the AWS Marketplace AMI (Amazon Machine Image) for Bitnami ResourceSpace"
  default     = "ami-0e3cecd2b3d50ee5f"
  type        = string
}

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
  default = "/login.php"
}

variable "common_tags" {
  description = "Common tags for created resources"
  default = {
    Application = "BC Parks DAM"
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
