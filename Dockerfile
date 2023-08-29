# Use the official PHP image from the dockerhub
FROM php:8.1-fpm

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# Set working directory
WORKDIR /var/www

# Remove the default nginx HTML files
RUN rm -rf /var/www/html

# Copy the Laravel app files to the container
COPY . /var/www

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run Composer install
RUN composer install

# Expose port 9000 to interact with the application
EXPOSE 9000
