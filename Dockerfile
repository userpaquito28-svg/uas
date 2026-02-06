# Gunakan PHP + Apache
FROM php:8.2-apache

# Disable semua MPM yang bikin conflict
RUN a2dismod mpm_event mpm_worker || true
# Enable prefork (PHP compatible)
RUN a2enmod mpm_prefork

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy semua file project
COPY . /var/www/html/

# Set permission aman
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
