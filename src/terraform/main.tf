# main.tf
# update 20260126

provider "aws" {
  region = var.aws_region
}

# Internal ALB 

# Create the Application Load Balancer
resource "aws_lb" "main" {
  name               = "bcparks-dam-alb"
  internal           = true
  load_balancer_type = "application"
  security_groups    = [local.network_resources.aws_security_groups.web.id]
  subnets            = local.network_resources.aws_subnet_ids.public.ids

  enable_deletion_protection = false

  tags = merge(
    var.common_tags,
    {
      Name = "bcparks-dam-alb"
    }
  )
}

# Redirect all traffic from the ALB to the target group
resource "aws_lb_listener" "web" {
  load_balancer_arn = aws_lb.main.arn
  port              = "443"
  protocol          = "HTTPS"
  ssl_policy        = "ELBSecurityPolicy-TLS-1-2-2017-01"
  certificate_arn   = var.certificate_arn  # You'll need to add this variable or reference an ACM certificate

  default_action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.app.arn
  }
}

resource "aws_alb_target_group" "app" {
  name                 = "bcparks-dam-vm"
  port                 = var.app_port
  protocol             = "HTTP"
  vpc_id               = local.network_resources.aws_vpc.id
  target_type          = "instance"
  deregistration_delay = 30

  health_check {
    healthy_threshold   = "2"
    interval            = "10"
    protocol            = "HTTP"
    matcher             = "200"
    timeout             = "5"
    path                = var.health_check_path
    unhealthy_threshold = "10"
  }

  #tags = var.common_tags
  tags = merge(
    var.common_tags,
    {
      #"LastUpdated" = formatdate("YYYYMMDDhhmmss", timestamp())
      "LastUpdated" = timestamp()
    }
  )
}

locals {
  domain_name = "${var.service_names[0]}.${var.licence_plate}-${var.target_env}.internal.stratus.cloud.gov.bc.ca"
}

data "template_file" "userdata_script" {
  template = file("userdata.tpl")
  vars = {
    git_url                   = var.git_url
    target_env                = var.target_env
    domain_name               = var.domain_name
    licence_plate             = var.licence_plate
    branch_name               = var.branch_name
    aws_region                = var.aws_region
    rds_endpoint              = aws_rds_cluster.mysql.endpoint
    efs_dns_name              = aws_efs_file_system.efs_filestore.dns_name
    mysql_username            = local.secrets.mysql_username
    mysql_password            = local.secrets.mysql_password
    email_notify              = local.secrets.email_notify
    email_from                = local.secrets.email_from
    spider_password           = local.secrets.spider_password
    scramble_key              = local.secrets.scramble_key
    api_scramble_key          = local.secrets.api_scramble_key
    technical_contact_name		=	local.secrets.technical_contact_name
    technical_contact_email		=	local.secrets.technical_contact_email
    secret_salt								=	local.secrets.secret_salt
    auth_admin_password				=	local.secrets.auth_admin_password
    saml_database_username		=	local.secrets.saml_database_username
    saml_database_password		=	local.secrets.saml_database_password
    sp_entity_id							=	local.secrets.sp_entity_id
    idp_entity_id							=	local.secrets.idp_entity_id
    single_signon_service_url	=	local.secrets.single_signon_service_url
    single_logout_service_url	=	local.secrets.single_logout_service_url
    x509_certificate					=	local.secrets.x509_certificate
  }
}

/* Auto Scaling & Launch Configuration */
module "asg" {
  source  = "terraform-aws-modules/autoscaling/aws"
  version = "7.4.0"

  name = "bcparks-dam-vm"

  # Launch template
  launch_template_name        = "dam-vm-lt"
  launch_template_description = "Launch template for BCParks DAM"
  
  image_id                    = var.image_id
  instance_type               = "t3a.large"
  
  security_groups             = [local.network_resources.aws_security_groups.web.id]
  iam_instance_profile_arn    = aws_iam_instance_profile.ec2_profile.arn
  user_data                   = base64encode(data.template_file.userdata_script.rendered)
  
  # Marketplace product code if needed
  # associate_public_ip_address = false
  
  block_device_mappings = [
    {
      device_name = "/dev/xvda"
      ebs = {
        volume_size = 10
        volume_type = "gp2"
        delete_on_termination = true
      }
    }
  ]

  # Auto scaling group creation
  vpc_zone_identifier       = local.network_resources.aws_subnet_ids.app.ids
  health_check_type         = "ELB"
  min_size                  = 1
  max_size                  = 1
  desired_capacity          = 1
  wait_for_capacity_timeout = 0
  health_check_grace_period = 500
  target_group_arns         = [aws_alb_target_group.app.arn]

  instance_refresh = {
    strategy = "Rolling"
    preferences = {
      min_healthy_percentage = 50
    }
    triggers = ["tag"]
  }
}

resource "aws_lb_listener_rule" "host_based_weighted_routing" {
  listener_arn = aws_lb_listener.web.arn

  action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.app.arn
  }

  condition {
    host_header {
      values = [for sn in var.service_names : "${sn}.*"]
    }
  }
}
