FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

# Ajustar permisos de Laravel
RUN chmod -R 775 storage bootstrap/cache

# ⚠️ Elimina los comandos que limpian o cachean configuración en el build
# (porque intentan conectar a la DB)
# RUN php artisan config:clear && php artisan route:clear && php artisan cache:clear

# Exponer puerto dinámico de Render
EXPOSE $PORT

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=$PORT
