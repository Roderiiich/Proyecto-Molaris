```dockerfile
# Imagen base de PHP 8.4 con Apache
FROM php:8.4-apache

# Instalar dependencias del sistema y extensiones de PHP requeridas
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip intl bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Configurar DocumentRoot de Apache apuntando a /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf

# Copiar Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Permitir ejecutar Composer como superusuario
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copiar archivos de Composer primero para aprovechar la caché de Docker
COPY composer.json composer.lock ./

# Instalar dependencias de producción
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Copiar el resto del proyecto
COPY . .

# Generar el autoloader final
RUN composer dump-autoload --optimize --no-dev

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Puerto de Apache
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
```
