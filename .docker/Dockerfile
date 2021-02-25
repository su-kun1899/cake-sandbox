FROM sukun1899/cakephp:4-php8

COPY ./ /var/www/cake_app/

RUN set -eux; \
    composer install \
        --no-dev \
        --prefer-dist \
        -d /var/www/cake_app;
