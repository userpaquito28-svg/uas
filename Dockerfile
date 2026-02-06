# Gunakan image PHP + Apache
FROM php

# Install ekstensi PDO + MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copy seluruh project
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/
