FROM php:8.1-apache

# Create directory and copy files
WORKDIR /var/www/html

# Install PostgreSQL PDO driver
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy all files
COPY . .

# Move index.php to root
RUN mv routes/index.php .

# Enable Apache rewrite module
RUN a2enmod rewrite

# Configure Apache to use mod_rewrite
RUN echo '\
<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n\
' >> /etc/apache2/apache2.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Enable error logging
RUN echo "log_errors = On" >> /usr/local/etc/php/php.ini
RUN echo "error_log = /dev/stderr" >> /usr/local/etc/php/php.ini

# List files for debugging
RUN echo "Contents of /var/www/html:" && ls -la /var/www/html/

CMD ["apache2-foreground"]


