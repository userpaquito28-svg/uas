# Base image PHP + Apache
FROM php:8.2-apache

# Install ekstensi database
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project ke web root
COPY . /var/www/html/

WORKDIR /var/www/html/
