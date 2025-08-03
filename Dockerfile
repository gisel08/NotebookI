FROM php:8.2-fpm

# Instalación de dependencias del sistema y Node.js
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
    npm # <--- ¡Aquí estaba el problema! Ahora está correctamente al final de la lista de 'apt-get install'

# Instalación de extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia el código
COPY . .

# Instala dependencias de Laravel (Composer)
RUN composer install --optimize-autoloader --no-dev

# Instala dependencias de Node.js (npm) y compila el frontend (Vite)
RUN npm install && npm run build # <--- Esto es CRUCIAL para Vite

# Asigna permisos
RUN chmod -R 755 /var/www && chown -R www-data:www-data /var/www

# Expone el puerto del servidor PHP
EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]