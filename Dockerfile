# Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# Disable MPM yang bentrok
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable rewrite (opsional)
RUN a2enmod rewrite

# Copy semua file project
COPY . /var/www/html/

# Set permission
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
