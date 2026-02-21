#!/bin/bash
rm -rf /var/app/current/bootstrap/cache/*.php
chown -R webapp:webapp /var/app/current/storage /var/app/current/bootstrap/cache
chmod -R 775 /var/app/current/storage /var/app/current/bootstrap/cache

cd /var/app/current
sudo -u webapp php artisan config:cache
sudo -u webapp php artisan route:cache
sudo -u webapp php artisan view:cache

systemctl restart php-fpm
