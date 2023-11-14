FROM php:8.1-cli

# Instala las herramientas necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip

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

# Expone el puerto que usa tu aplicación (si es necesario)
EXPOSE 80

# Comando por defecto para ejecutar la API
CMD php -S 0.0.0.0:80 -t /var/www/html