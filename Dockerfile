# Stage 1: Build frontend (Tailwind/Vite)
FROM node:18 AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Laravel with Apache + PHP
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite and set DocumentRoot to /public
RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy application files
COPY . .

# Copy built frontend assets from Node stage
COPY --from=node-builder /app/public/build ./public/build

# Install PHP dependencies fully (now artisan exists)
RUN composer install --optimize-autoloader --no-dev

# Generate APP_KEY if missing
RUN php artisan key:generate --force

# Run migrations automatically on container start
CMD php artisan migrate --force && apache2-foreground

# Permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
