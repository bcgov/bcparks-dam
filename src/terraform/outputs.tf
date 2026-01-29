output "db" {
  description = "Aurora database endpoint"
  value       = aws_rds_cluster.mysql.endpoint
}

output "alb_dns_name" {
  description = "DNS name of the Application Load Balancer"
  value       = aws_lb.main.dns_name
}

output "url" {
  description = "Base URL for Resourcespace"
  value       = "https://${var.service_names[0]}.${var.licence_plate}-${var.target_env}.internal.stratus.cloud.gov.bc.ca/"
}