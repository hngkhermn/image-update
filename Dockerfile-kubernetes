# Menggunakan base image PHP dengan PHP-FPM
FROM php:8.2-fpm

# Menyimpan direktori kerja di dalam container
WORKDIR /var/www/html

# Menyalin semua file dari folder proyek ke direktori kerja container
COPY . .

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
    libzip-dev 

# Menginstall ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Mengubah izin file agar bisa diakses oleh Nginx
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html