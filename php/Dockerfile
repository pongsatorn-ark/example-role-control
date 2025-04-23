FROM php:8.3-fpm

# Copy custom config (optional)
COPY php.ini /usr/local/etc/php/

RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install redis && docker-php-ext-enable redis

# Set permissions for sessions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www
