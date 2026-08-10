#!/bin/bash

# 1. تنظيف كاش لارافيل بالكامل
php artisan config:clear
php artisan cache:clear

# 2. تشغيل الـ Migration لإنشاء الجداول في PostgreSQL
php artisan migrate --force

# 3. تشغيل Apache
exec apache2-foreground