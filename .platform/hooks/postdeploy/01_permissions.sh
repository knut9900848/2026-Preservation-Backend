#!/bin/bash
set -e

# 배포 후 권한 재설정
chown -R webapp:webapp /var/app/current/storage
chown -R webapp:webapp /var/app/current/bootstrap/cache
chmod -R 775 /var/app/current/storage
chmod -R 775 /var/app/current/bootstrap/cache

# 로그 파일 권한
touch /var/app/current/storage/logs/laravel.log
chown webapp:webapp /var/app/current/storage/logs/laravel.log
chmod 664 /var/app/current/storage/logs/laravel.log

echo "postdeploy: 권한 재설정 완료"
