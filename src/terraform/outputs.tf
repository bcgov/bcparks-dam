locals {
  effective_custom_domain = var.custom_domain_name != "" ? var.custom_domain_name : (
    var.target_env == "dev"  ? "dev-images.bcparks.ca" :
    var.target_env == "test" ? "test-images.bcparks.ca" :
    var.target_env == "prod" ? "images.bcparks.ca" : ""
  )
}

output "db" {
  description = "Aurora database endpoint"
  value       = aws_rds_cluster.mysql.endpoint
}

output "alb_dns_name" {
  description = "DNS name of the Application Load Balancer"
  value       = aws_lb.main.dns_name
}

output "cloudfront_domain_name" {
  description = "CloudFront distribution domain name"
  value       = length(aws_cloudfront_distribution.main) > 0 ? aws_cloudfront_distribution.main[0].domain_name : ""
}

output "cloudfront_distribution_id" {
  description = "CloudFront distribution ID"
  value       = length(aws_cloudfront_distribution.main) > 0 ? aws_cloudfront_distribution.main[0].id : ""
}

output "url" {
  description = "Base URL for Resourcespace"
  value       = var.enable_cloudfront && local.effective_custom_domain != "" ? "https://${local.effective_custom_domain}/" : "https://${var.service_names[0]}.${var.licence_plate}-${var.target_env}.stratus.cloud.gov.bc.ca/"
}