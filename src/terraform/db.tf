# db.tf

resource "aws_db_subnet_group" "data_subnet" {
  name                   = "data-subnet"
  subnet_ids             = module.network.aws_subnet_ids.data.ids

  tags = var.common_tags
}

resource "aws_rds_cluster" "mysql" {
  cluster_identifier      = "bcparks-dam-mysql-cluster"
  engine                  = "aurora-mysql"
  engine_version          = "8.0.mysql_aurora.3.08.2"
  serverlessv2_scaling_configuration {
    min_capacity = 2
    max_capacity = 16
  }
  
  database_name           = "resourcespace"
  master_username         = local.secrets.mysql_username
  master_password         = local.secrets.mysql_password
  backup_retention_period = 5
  preferred_backup_window = "07:00-09:00"
  db_subnet_group_name    = aws_db_subnet_group.data_subnet.name
  storage_encrypted       = true
  vpc_security_group_ids  = [aws_security_group.rds_security_group.id]
  skip_final_snapshot     = true
  enable_http_endpoint    = true
  final_snapshot_identifier = "resourcespace-finalsnapshot"

  tags = var.common_tags
}

resource "aws_rds_cluster_instance" "mysql" {
  identifier         = "bcparks-dam-mysql-cluster-instance-1"
  cluster_identifier = aws_rds_cluster.mysql.id

  engine         = "aurora-mysql"
  instance_class = "db.serverless"

  publicly_accessible   = false
  db_subnet_group_name  = aws_db_subnet_group.data_subnet.name
  performance_insights_enabled = false

  tags = var.common_tags
}