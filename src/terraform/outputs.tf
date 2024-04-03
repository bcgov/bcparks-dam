output "url" {
  description = "Base URL for Resourcspace"
  value       = "https://${var.service_names[0]}.[LICENCEPLATE]-${var.target_env}.nimbus.cloud.gov.bc.ca/"
}

output "db" {
  description = "Aurora database endpoint"
  value       = aws_rds_cluster.mysql.endpoint
}

output "apigw_url" {
  description = "Base URL for API Gateway stage"
  value       = aws_apigatewayv2_api.app.api_endpoint
}

/*
output "cloudfront" {
  description = "CloudFront distribution for the ALB"
  value = {
    domain_name     = aws_cloudfront_distribution.alb_distribution.domain_name
    distribution_id = aws_cloudfront_distribution.alb_distribution.id
  }
}
*/

output "cloudfront_distribution_domain" {
  description = "The domain name of the CloudFront distribution"
  value       = aws_cloudfront_distribution.alb_distribution.domain_name
}
