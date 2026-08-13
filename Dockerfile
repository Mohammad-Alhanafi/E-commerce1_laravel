# ================================
# المرحلة 1: بناء ملفات CSS/JS بواسطة Vite
# ================================
FROM node:20 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ================================
# المرحلة 2: صورة PHP الرئيسية
# ================================
FROM php:8.2-apache

# تثبيت الإضافات والمكتبات المطلوبة لـ Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# تفعيل خاصية mod_rewrite في Apache لتوجيه روابط Laravel
RUN a2enmod rewrite

# ضبط المجلد الرئيسي داخل الخادم
WORKDIR /var/www/html

# نسخ ملفات مشروعك إلى داخل الخادم
COPY . .

# نسخ ملفات الـ build الجاهزة من المرحلة الأولى (Vite)
COPY --from=node_builder /app/public/build /var/www/html/public/build

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تثبيت حزم المكتبات الخاصة بـ Laravel
RUN composer install --no-dev --optimize-autoloader

# ضبط توجيه Apache ليعمل من مجلد public الخاص بـ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# إعطاء صلاحيات التعديل لمجلدات التخزين والـ Cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# إعطاء صلاحية التنفيذ لملف entrypoint.sh
RUN chmod +x /var/www/html/entrypoint.sh

# فتح المنفذ 80
EXPOSE 80

# تشغيل entrypoint.sh عند بدء الحاوية (يشغل الميغريشن ثم Apache)
ENTRYPOINT ["/var/www/html/entrypoint.sh"]