# Use PHP with Apache base image
FROM php:8.3-apache

# Install PHP extensions and system dependencies
RUN apt-get update && apt-get install -y \
    curl gnupg2 git unzip nano nodejs npm supervisor \
    libzip-dev libpng-dev libonig-dev pkg-config \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y yarn \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set Apache ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy application source code
COPY . .

# Install Composer globally and PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-interaction --optimize-autoloader

# Install frontend dependencies and build assets
RUN yarn install && yarn build

# Set proper permissions
RUN chmod -R 777 storage bootstrap/cache && \
    chown -R www-data:www-data /var/www/html

# Add Apache virtual host config to serve from Laravel's public directory
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Copy custom entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose HTTP port
EXPOSE 8000

# Start the entrypoint script
CMD ["/usr/local/bin/entrypoint.sh"]
