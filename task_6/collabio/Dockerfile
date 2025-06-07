# Dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update \
  && apt-get install -y \
       git unzip zlib1g-dev libicu-dev libonig-dev libxml2-dev libzip-dev \
       libpng-dev libjpeg-dev libfreetype6-dev \
  && docker-php-ext-install intl pdo pdo_mysql zip opcache \
  && pecl install apcu \
  && docker-php-ext-enable apcu

# Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Install PHP dependencies (without scripts/optimizations)
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-interaction

# Copy the rest of the application
COPY . .

# Optimize autoloader
RUN composer dump-autoload --optimize

# Copy & enable custom entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
