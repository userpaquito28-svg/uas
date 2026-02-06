# Gunakan PHP dengan Apache + MySQL extension
FROM php:8.2-apache

# Install mysqli & pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project ke container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/
