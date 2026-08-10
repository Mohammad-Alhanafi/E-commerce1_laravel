# استخدام صورة PHP رسمية مع سيرفر Apache
FROM php:8.2-apache

# تثبيت الإضافات والمكتبات المطلوبة لـ Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# تفعيل خاصية mod_rewrite في Apache لتوجيه روابط Laravel
RUN a2enmod rewrite

# ضبط المجلد الرئيسي داخل الخادم
WORKDIR /var/www/html

# نسخ ملفات مشروعك إلى داخل الخادم
COPY . .

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تثبيت حزم المكتبات الخفيفة الخاصة بـ Laravel
RUN composer install --no-dev --optimize-autoloader

# ضبط توجيه Apache ليعمل من مجلد public الخاص بـ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# إعطاء صلاحيات التعديل لمجلدات التخزين والـ Cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# فتح المنفذ 80
EXPOSE 80

# تشغيل أمر الـ Migration ثم تشغيل Apache فور بدء الحاوية
# إعطاء صلاحية التنفيذ للـ entrypoint
# تشغيل الـ Migration تلقائياً ثم إطلاق سيرفر Apache
CMD php artisan migrate --force ; exec apache2-foreground