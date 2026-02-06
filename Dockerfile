FROM php:8.2-fpm

# Install ekstensi
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project
COPY . /var/www/html/

WORKDIR /var/www/html/
