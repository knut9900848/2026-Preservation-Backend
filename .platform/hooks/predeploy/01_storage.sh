#!/bin/bash
set -e

# storage 디렉토리 구조 생성
mkdir -p /var/app/staging/storage/framework/views
mkdir -p /var/app/staging/storage/framework/cache
mkdir -p /var/app/staging/storage/framework/sessions
mkdir -p /var/app/staging/storage/logs
mkdir -p /var/app/staging/bootstrap/cache

# 권한 설정
chown -R webapp:webapp /var/app/staging/storage
chown -R webapp:webapp /var/app/staging/bootstrap/cache
chmod -R 775 /var/app/staging/storage
chmod -R 775 /var/app/staging/bootstrap/cache

# 로그 파일 생성
touch /var/app/staging/storage/logs/laravel.log
chown webapp:webapp /var/app/staging/storage/logs/laravel.log
chmod 664 /var/app/staging/storage/logs/laravel.log

echo "predeploy: storage 디렉토리 및 권한 설정 완료"
