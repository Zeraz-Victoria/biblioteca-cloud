# Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 1. Instalamos dependencias del sistema necesarias para Postgres (libpq-dev)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Habilitamos mod_rewrite de Apache (útil para URLs amigables en el futuro)
RUN a2enmod rewrite