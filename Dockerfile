FROM php:8.2-fpm

# Install system dependencies and Node.js
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy the code
COPY . .

# Install Laravel dependencies (Composer)
RUN composer install --optimize-autoloader --no-dev

# Install Node.js dependencies (npm) and build the frontend (Vite)
RUN npm install && npm run build

# Assign permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Expose PHP-FPM port (for NGINX in docker-compose)
EXPOSE 9000

# Command to start PHP-FPM
CMD ["php-fpm"]