# cake-sandbox

## Quick Start Guide

```sh
# start
docker-compose up --build

# composer
docker exec \
  -e COMPOSER_PROCESS_TIMEOUT=600 \
  cake-sandbox-web \
  composer install \
    --prefer-dist \
    -d /var/www/cake_app

# setup .env
cp config/.env.example config/.env
```

`http://localhost:8080` にブラウザからアクセス。

## Run Tests

```sh
docker exec \
  cake-sandbox-web \
  vendor/bin/phpunit
```

## Connect database

```sh
mysql -u root -p -h 127.0.0.1 -P 13306
```
