# Use the official PHP 8.3 Apache image as the base
FROM php:8.3-apache

# Install necessary PHP extensions and dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    libzip-dev libcurl4-openssl-dev libpng-dev libonig-dev unzip && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip mysqli pdo pdo_mysql gd mbstring curl opcache && \
    rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure OPcache
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Suppress Apache ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set the working directory
WORKDIR /var/www/html/

# Copy all application code
COPY . /var/www/html/
RUN chmod -R 0777 /var/www/html/storage /var/www/html/bootstrap/cache
# Give full permissions to specific folders

# Run Composer commands
RUN composer install --no-interaction --no-scripts --optimize-autoloader && \
    composer update --no-interaction --no-scripts --optimize-autoloader

RUN mkdir -p /var/www/html/bootstrap/cache && \
    chmod -R 0777 /var/www/html/bootstrap/cache
    
# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
