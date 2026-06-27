#!/bin/bash
SITE_DIR = "/var/www/vps-petroprog.vps.section-inf.tech/public"
BACKUP_DIR = "/home/petroprog/backup_files"
DATE=$(date +%Y-%m-%d)

mkdir -p $BACKUP_DIR

tar -czf $BACKUP_DIR/site_files_$DATE.tar.gz $SITE_DIR

find $BACKUP_DIR -type f -name "*.tar.gz" -mtime +14 -delete
