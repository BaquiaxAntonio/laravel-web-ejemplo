FROM php:8.2-apache

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite3 \
    libsqlite3-dev

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos del proyecto
COPY . /var/www/html

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node y construir assets
RUN npm ci && npm run build

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Crear base de datos SQLite si no existe
RUN touch database/database.sqlite

# Ejecutar migraciones
RUN php artisan migrate --force || true

# Limpiar cache y configurar Laravel para producción
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan route:clear

# Configurar Apache para Laravel
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
        Options Indexes FollowSymLinks\n\
        php_value upload_max_filesize 100M\n\
        php_value post_max_size 100M\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Crear archivo .htaccess si no existe
RUN if [ ! -f /var/www/html/public/.htaccess ]; then \
    echo '<IfModule mod_rewrite.c>\n\
    <IfModule mod_negotiation.c>\n\
        Options -MultiViews -Indexes\n\
    </IfModule>\n\
\n\
    RewriteEngine On\n\
\n\
    # Handle Authorization Header\n\
    RewriteCond %{HTTP:Authorization} .\n\
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]\n\
\n\
    # Redirect Trailing Slashes If Not A Folder...\n\
    RewriteCond %{REQUEST_FILENAME} !-d\n\
    RewriteCond %{REQUEST_URI} (.+)/$\n\
    RewriteRule ^ %1 [L,R=301]\n\
\n\
    # Send Requests To Front Controller...\n\
    RewriteCond %{REQUEST_FILENAME} !-d\n\
    RewriteCond %{REQUEST_FILENAME} !-f\n\
    RewriteRule ^ index.php [L]\n\
</IfModule>' > /var/www/html/public/.htaccess; \
    fi

# Exponer puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]