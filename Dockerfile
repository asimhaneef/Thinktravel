FROM php:8.3-apache

# Install PHP and system dependencies
RUN apt-get update && apt-get install -y \
    curl gnupg2 git unzip nano nodejs npm supervisor \
    libzip-dev libpng-dev libonig-dev pkg-config \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y yarn \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy app source
COPY . .

# Install Composer globally and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-interaction --optimize-autoloader

# Install Node packages
RUN yarn install
RUN yarn build

# Laravel permissions
RUN chmod -R 777 storage bootstrap/cache

# Copy and use the custom bash script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY . .

# Expose Laravel and Vite ports
EXPOSE 8000 5173

# Run both Laravel and Vite via bash script
CMD ["/usr/local/bin/entrypoint.sh"]
