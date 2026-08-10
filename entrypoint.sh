#!/bin/bash

# تنفيذ الـ Migration عند كل إطلاق للحاوية
php artisan migrate --force

# تشغيل سيرفر Apache الأساسي
exec apache2-foreground