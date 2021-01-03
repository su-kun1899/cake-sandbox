FROM php:8.0-apache

# setup Apache
COPY .docker/etc/apache2/ /etc/apache2/
