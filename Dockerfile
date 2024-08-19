# Dockerfile
FROM php:8.1-apache

# Enable mod_rewrite
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql

# Copy custom Apache configuration
COPY apache-config.conf /etc/apache2/conf-available/apache-config.conf
RUN a2enconf apache-config

# Copy application source
COPY / /var/www/html/gator
