# Imagen base de PHP con Apache
FROM php:8.3-apache

# Instalar dependencias del sistema y extensiones de PHP requeridas
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip intl bcmath

# Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Configurar el DocumentRoot de Apache apuntando a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Copiar Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Permiso para ejecutar Composer como superusuario
ENV COMPOSER_ALLOW_SUPERUSER=1

# 1. Copiar primero los archivos de Composer para aprovechar la caché de Docker
COPY composer.json composer.lock ./

# 2. Instalar dependencias omitiendo chequeos rígidos de plataforma
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# 3. Copiar el resto del código del proyecto
COPY . .

# Generar el autoloader final de Composer con todos los archivos copiados
RUN composer dump-autoload --optimize --no-dev

# Ajustar permisos para storage y bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Puerto expuesto
EXPOSE 80

CMD ["apache2-foreground"]