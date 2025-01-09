# Menggunakan base image PHP dengan PHP-FPM 
FROM php:8.2-fpm

# Menyimpan direktori kerja di dalam container
WORKDIR /var/www/html

# Menyalin semua file dari folder proyek ke direktori kerja container
COPY . .

# Salin file .env.example ke .env
RUN cp .env.example .env

# Install dependensi sistem yang diperlukan
RUN apt-get update && apt-get install -y \
    build-essential \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nano \
    unzip \
    git \
    curl \
    libzip-dev \
    nginx \
    && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install mysqli pdo pdo_mysql gd

# Install Composer (untuk mengelola dependensi PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependensi PHP untuk Laravel (Composer install)
RUN composer install --no-dev --optimize-autoloader

# Generate Laravel application key
RUN php artisan key:generate

# Set izin untuk direktori Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Konfigurasi PHP-FPM agar bekerja dengan Nginx
COPY ./docker/nginx/nginx.conf /etc/nginx/sites-available/default

# Mengatur port yang akan digunakan (default HTTP port)
EXPOSE 80

# Start Nginx dan PHP-FPM
CMD service nginx start && php-fpm
