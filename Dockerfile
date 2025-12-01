# Use PHP with Apache as base image
FROM php:8.2-apache

# Set timezone to Brunei (UTC+8)
ENV TZ=Asia/Brunei
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/timezone.ini

# Copy application files
COPY *.html /var/www/html/
COPY *.jpg /var/www/html/
COPY api/ /var/www/html/api/
COPY js/ /var/www/html/js/
COPY database/ /var/www/html/database/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

