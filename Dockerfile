FROM php:8.1.1-apache

# Install PDO and MySQL extensions with all dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-enable pdo pdo_mysql mysqli
