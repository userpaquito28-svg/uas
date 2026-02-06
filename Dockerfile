# Gunakan PHP 8.2 + Apache
FROM php:8.2-apache

# Nonaktifkan MPM bentrok
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# Install ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable rewrite module
RUN a2enmod rewrite

# Copy semua file project
COPY . /var/www/html/

# Set permission aman
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
