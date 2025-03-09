FROM php:8.1.1-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PDO and MySQL extensions with all dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-enable pdo pdo_mysql mysqli

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install MinIO PHP SDK
WORKDIR /var/www/html
RUN composer require aws/aws-sdk-php
