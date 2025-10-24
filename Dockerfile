FROM php:8.2-cli

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copiar archivos
COPY . /app

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Crear directorio para SQLite
RUN mkdir -p /app/database && \
    touch /app/database/database.sqlite && \
    chmod -R 775 /app/database /app/storage /app/bootstrap/cache

# Optimizar Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer puerto
EXPOSE 8080

# Comando de inicio
CMD touch /app/database/database.sqlite && \
    chmod 664 /app/database/database.sqlite && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8080