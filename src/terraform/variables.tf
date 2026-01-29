# variables.tf

variable "app_name" {
  description = "BC Parks DAM"
  type        = string
  default     = "bcparks-dam"
}

variable "image_id" {
  description = "id of the AWS AMI (Amazon Machine Image) for Bitnami ResourceSpace"
  default     = "ami-02c17467e982e368f" #Debian 13
  type        = string
}
#previous: ami-0614020a2c066706c (Debian 12)
#previous: ami-0adad14dcb2ca073f

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
  default = "/health-check.php"
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
  default     = "dam.e0806e-dev.internal.stratus.cloud.gov.bc.ca"
  type        = string
}

variable "licence_plate" {
  description = "The licence plate for the application"
  type        = string
}

variable "web_security_group_name" {
  description = "Name of the pre-provisioned web security group"
  type        = string
  default     = "Web"
}

variable "app_security_group_name" {
  description = "Name of the pre-provisioned app security group"
  type        = string
  default     = "App"
}

variable "data_security_group_name" {
  description = "Name of the pre-provisioned data security group"
  type        = string
  default     = "Data"
}

variable "vpc_filter_key" {
  description = "The tag key to filter VPC by"
  type        = string
  default     = "Environment"
}

variable "vpc_name_tag_map" {
  description = "Map of environment names to VPC name tags"
  type        = map(string)
  default = {
    dev  = "Dev"
    test = "Test"
    prod = "Prod"
  }
}