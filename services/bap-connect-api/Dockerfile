FROM php:8.3-fpm

LABEL maintainer="Phan Van Vu <phanvuars@gmail.com>"

# Set working directory
WORKDIR /var/www/html

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    curl \
    nodejs npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions for php
RUN docker-php-ext-install pdo_pgsql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Assign permissions of the working directory
RUN chown -R www-data:www-data /var/www/html
