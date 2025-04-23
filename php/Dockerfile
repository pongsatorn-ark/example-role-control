FROM php:8.3-fpm

# Copy custom config (optional)
COPY php.ini /usr/local/etc/php/

# Set permissions for sessions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www
