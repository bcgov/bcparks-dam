# network.tf

# Data sources to reference pre-provisioned networking resources
data "aws_vpc" "main" {
  filter {
    name   = "tag:Name"
    values = [var.vpc_name_tag_map[var.target_env]]
  }
}

data "aws_security_group" "web" {
  name   = var.web_security_group_name
  vpc_id = data.aws_vpc.main.id
}

data "aws_security_group" "app" {
  name   = var.app_security_group_name
  vpc_id = data.aws_vpc.main.id
}

data "aws_security_group" "data" {
  name   = var.data_security_group_name
  vpc_id = data.aws_vpc.main.id
}

# Data source to get public subnets
data "aws_subnets" "public" {
  filter {
    name   = "vpc-id"
    values = [data.aws_vpc.main.id]
  }

  filter {
    name   = "tag:Name"
    values = ["*Web*"]
  }
}

# Data source to get app subnets
data "aws_subnets" "app" {
  filter {
    name   = "vpc-id"
    values = [data.aws_vpc.main.id]
  }

  filter {
    name   = "tag:Name"
    values = ["*App*"]
  }
}

# Data source to get data subnets
data "aws_subnets" "data" {
  filter {
    name   = "vpc-id"
    values = [data.aws_vpc.main.id]
  }

  filter {
    name   = "tag:Name"
    values = ["*Data*"]
  }
}

# Create a local output structure to match what the module provided
locals {
  network_resources = {
    aws_vpc = data.aws_vpc.main
    aws_security_groups = {
      web = data.aws_security_group.web
      app = data.aws_security_group.app
      data = data.aws_security_group.data
    }
    aws_subnet_ids = {
      public = data.aws_subnets.public
      app    = data.aws_subnets.app
      data   = data.aws_subnets.data
    }
  }
}