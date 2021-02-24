# cake-sandbox

## Quick Start Guide

```sh
# start
docker-compose up --build

# composer
docker exec \
  cake-sandbox-web \
  composer install \
    --prefer-dist \
    -d /var/www/cake_app

# setup .env
cp config/.env.example config/.env

# create database
docker exec \
  cake-sandbox-web \
  bin/cake migrations migrate

# seed data
docker exec \
  cake-sandbox-web \
  bin/cake migrations seed --seed DatabaseSeed
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
