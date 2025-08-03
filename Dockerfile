FROM php:8.2-fpm

# Instalación de dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia el código
COPY . .

# Instala dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Asigna permisos
RUN chmod -R 755 /var/www && chown -R www-data:www-data /var/www

# Expone el puerto del servidor PHP
EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]