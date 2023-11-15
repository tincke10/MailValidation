FROM php:8.1-apache

# Instala las herramientas necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copia composer.json y composer.lock (si existe)
COPY composer.json composer.lock ./

# Instala las dependencias con Composer
RUN composer install

# Copia el resto del código de la aplicación
COPY . .

# Cambia el propietario del directorio /var/www/html a www-data
RUN chown -R www-data:www-data /var/www/html

# Expone el puerto que usa tu aplicación (si es necesario)
EXPOSE 80