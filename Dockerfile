
# ============================================================
# ETAPA 1: Compilar frontend con Node y Vite
# ============================================================
FROM node:22 AS frontend

WORKDIR /var/www/html

# Copiar archivos de npm
COPY package.json package-lock.json ./

# Instalar dependencias
RUN npm ci

# Copiar archivos necesarios para Vite
COPY . .

# Compilar assets de producción
RUN npm run build


# ============================================================
# ETAPA 2: PHP 8.4 + Apache + Laravel
# ============================================================
FROM php:8.4-apache

# Instalar dependencias del sistema y extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
        intl \
        bcmath \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar DocumentRoot de Apache apuntando a /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf

# Copiar Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Permitir ejecutar Composer como superusuario
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copiar archivos de Composer primero
COPY composer.json composer.lock ./

# Instalar dependencias de producción
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Copiar el proyecto completo
COPY . .

# Copiar los assets compilados por Vite
COPY --from=frontend /var/www/html/public/build ./public/build

# Generar autoloader final
RUN composer dump-autoload --optimize --no-dev

# Crear/ajustar permisos necesarios
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/public/build

# Puerto de Apache
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]

