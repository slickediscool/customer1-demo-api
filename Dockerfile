FROM php:8.1-apache

# Copy application files
COPY . /var/www/html/

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


# Expose port 9000
EXPOSE 9000
CMD ["php-fpm"]
