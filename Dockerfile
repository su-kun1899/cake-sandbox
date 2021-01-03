FROM php:8.0-apache

# setup Apache
COPY .docker/etc/apache2/ /etc/apache2/

# install composer
RUN curl -s https://getcomposer.org/installer \
      | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer \
        --2
