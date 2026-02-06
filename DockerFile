FROM php:8.2-apache

# Disable MPM lain
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# Install mysqli
RUN docker-php-ext-install mysqli

# Enable rewrite
RUN a2enmod rewrite

# Copy project
COPY . /var/www/html/

# Permission
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
