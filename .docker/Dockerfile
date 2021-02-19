FROM sukun1899/cakephp:4-php8

# setup xdebug for dev
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
COPY web/usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
