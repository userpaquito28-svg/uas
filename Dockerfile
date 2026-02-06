# Gunakan image PHP + Apache
FROM php:8.2-apache

# Install ekstensi PDO + MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copy seluruh project
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/
