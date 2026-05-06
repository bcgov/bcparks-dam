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

output "cloudfront_origin_domain" {
  description = "Origin hostname used by CloudFront"
  value       = local.cloudfront_origin_domain
}

output "url" {
  description = "Base URL for Resourcespace"
  value       = var.enable_cloudfront && local.effective_custom_domain != "" ? "https://${local.effective_custom_domain}/" : "https://${local.cloudfront_origin_domain}/"
}