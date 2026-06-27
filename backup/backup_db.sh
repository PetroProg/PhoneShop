#!/bin/bash

DB_USER="site_user"
DB_PASS="user_password_123"
DB_NAME="db_magasin_electronique"
BACKUP_DIR="/home/petroprog/backups"
DATE=$(date +%Y-%m-%d_%H-%M-%S)

mkdir -p $BACKUP_DIR

mariadbcheck -u $DB_USER -p$DB_PASS --optimize $DB_NAME

mariadb-dump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/${DB_NAME}_$DATE.sql

find $BACKUP_DIR -type f -name "*.sql" -mtime +7 -delete

