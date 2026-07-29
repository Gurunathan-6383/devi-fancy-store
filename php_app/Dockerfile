FROM php:8.2-apache

# Install PHP extensions required by this project
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Copy custom Apache vhost config (DocumentRoot = /var/www/html/public)
COPY php_app/docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copy only the PHP application from the subdirectory
COPY php_app/ /var/www/html/

# Ensure Apache-owned writable directories exist
RUN mkdir -p storage/logs && chown -R www-data:www-data storage/

COPY php_app/docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
