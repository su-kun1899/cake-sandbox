FROM php:8.0-apache

# setup Apache
COPY .docker/etc/apache2/ /etc/apache2/

# install apt packages
RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends git libicu-dev libonig-dev libxml2-dev; \
	rm -rf /var/lib/apt/lists/*;

# install composer
RUN curl -s https://getcomposer.org/installer \
      | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer \
        --2

# install php extentions
RUN docker-php-ext-install intl pdo_mysql

WORKDIR /var/www
