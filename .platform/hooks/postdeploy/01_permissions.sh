#!/bin/bash
# Laravel 디렉토리 권한 강제 설정
chown -R webapp:webapp /var/app/current/storage
chown -R webapp:webapp /var/app/current/bootstrap/cache
chmod -R 775 /var/app/current/storage
chmod -R 775 /var/app/current/bootstrap/cache

# 로그 파일 생성 및 권한 부여
touch /var/app/current/storage/logs/laravel.log
chown webapp:webapp /var/app/current/storage/logs/laravel.log
chmod 664 /var/app/current/storage/logs/laravel.log
