FROM php:8.2-apache

# Copier ton projet dans Apache
COPY . /var/www/html/

# Installer MySQL pour PHP
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite (optionnel mais pro)
RUN a2enmod rewrite

EXPOSE 80
