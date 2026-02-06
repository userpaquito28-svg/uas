FROM php:8.2-apache

# Install mysqli & pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Hapus modul MPM lain yang mungkin conflict
RUN a2dismod mpm_event mpm_worker || true

# Pastikan hanya satu MPM aktif (default prefork)
RUN a2enmod mpm_prefork

# Copy project ke container
COPY . /var/www/html/

WORKDIR /var/www/html/
