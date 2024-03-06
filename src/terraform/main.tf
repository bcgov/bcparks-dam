# main.tf

provider "aws" {
  region = var.aws_region
}

locals {
  common_tags        = var.common_tags
}



# API Gateway

resource "aws_apigatewayv2_vpc_link" "app" {
  name               = var.app_name
  subnet_ids         = module.network.aws_subnet_ids.web.ids
  security_group_ids = [module.network.aws_security_groups.web.id]
}

resource "aws_apigatewayv2_api" "app" {
  name          = var.app_name
  protocol_type = "HTTP"
}

resource "aws_apigatewayv2_integration" "app" {
  api_id             = aws_apigatewayv2_api.app.id
  integration_type   = "HTTP_PROXY"
  connection_id      = aws_apigatewayv2_vpc_link.app.id
  connection_type    = "VPC_LINK"
  integration_method = "ANY"
  integration_uri    = aws_alb_listener.internal.arn
}

resource "aws_apigatewayv2_route" "app" {
  api_id    = aws_apigatewayv2_api.app.id
  route_key = "ANY /{proxy+}"
  target    = "integrations/${aws_apigatewayv2_integration.app.id}"
}

resource "aws_apigatewayv2_stage" "app" {
  api_id      = aws_apigatewayv2_api.app.id
  name        = "$default"
  auto_deploy = true
}


# Internal ALB

resource "aws_alb" "app-alb" {
  name                             = var.app_name
  internal                         = true
  subnets                          = module.network.aws_subnet_ids.web.ids
  security_groups                  = [module.network.aws_security_groups.web.id]
  enable_cross_zone_load_balancing = true
  tags                             = local.common_tags

  lifecycle {
    ignore_changes = [access_logs]
  }
}
resource "aws_alb_listener" "internal" {
  load_balancer_arn = aws_alb.app-alb.arn
  port              = "80"
  protocol          = "HTTP"

  default_action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.app.arn
  }

}
resource "aws_alb_target_group" "app" {
  name                 = "${var.app_name}-tg"
  port                 = var.app_port
  protocol             = "HTTP"
  vpc_id               = module.network.aws_vpc.id
  target_type          = "instance" # was "ip"
  deregistration_delay = 30

  health_check {
    healthy_threshold   = "2"
    interval            = "5"
    protocol            = "HTTP"
    matcher             = "200"
    timeout             = "3"
    path                = var.health_check_path
    unhealthy_threshold = "2"
  }

  tags = local.common_tags
}




# CloudFront distribution

resource "aws_cloudfront_distribution" "alb_distribution" {
  enabled = true

  origin {
    domain_name = aws_alb.app-alb.dns_name
    origin_id   = "BCParksDAMOrigin"

    custom_origin_config {
      http_port                = 80
      https_port               = 443
      origin_protocol_policy   = "https-only"
      origin_ssl_protocols     = ["TLSv1.2"]
    }
  }

  default_cache_behavior {
    allowed_methods  = ["DELETE", "GET", "HEAD", "OPTIONS", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD"]
    target_origin_id = "BCParksDAMOrigin"

    forwarded_values {
      query_string = false
      cookies {
        forward = "none"
      }
    }

    viewer_protocol_policy = "redirect-to-https"
    min_ttl                = 0
    default_ttl            = 3600
    max_ttl                = 86400
  }

  restrictions {
    geo_restriction {
      restriction_type = "none"
    }
  }

  viewer_certificate {
    cloudfront_default_certificate = true
  }
}

















# User data script

data "template_file" "userdata_script" {
  template = file("userdata.tpl")
  vars = {
    git_url                   = var.git_url
    target_env                = var.target_env
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

# Auto Scaling & Launch Configuration
module "asg" {
  source  = "terraform-aws-modules/autoscaling/aws"
  version = "5.0.0"

  name = "bcparks-dam-vm"
  tags = var.common_tags

  # Launch configuration creation
  lc_name                   = var.lc_name
  image_id                  = var.image_id
  security_groups           = [module.network.aws_security_groups.web.id]

  #instance_type             = (var.target_env != "prod" ? "t3a.micro" : "t3a.small")
  instance_type             = "t3a.medium"

  iam_instance_profile_name = aws_iam_instance_profile.ec2_profile.name
  user_data                 = data.template_file.userdata_script.rendered
  use_lc                    = true
  create_lc                 = true

  root_block_device = [
    {
      volume_size = "10"
      volume_type = "gp2"
    },
  ]

  # Auto scaling group creation
  vpc_zone_identifier       = module.network.aws_subnet_ids.app.ids
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
