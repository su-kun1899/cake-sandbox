# cake-sandbox

[![CakePHP App CI](https://github.com/su-kun1899/cake-sandbox/actions/workflows/ci.yml/badge.svg)](https://github.com/su-kun1899/cake-sandbox/actions/workflows/ci.yml)

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

# set up for local dev
docker exec \
  cake-sandbox-web \
  composer run-script setup-local-cmd \
    -d /var/www/cake_app

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

## packaging

```shell
# build
docker build -f .docker/Dockerfile -t cake-sandobox .

# run cmd example
docker run \
  --name my-cake-sandobox \
  -p 8081:80 \
  -e "APP_NAME=my_app" \
  -e "DEBUG=true" \
  -e "APP_ENCODING=UTF-8" \
  -e "APP_DEFAULT_LOCALE=en_US" \
  -e "APP_DEFAULT_TIMEZONE=UTC" \
  -e "SECURITY_SALT=09591c1025125740b8ceff2fdd107fd966518e8e3c08eaec89154938af568aef" \
  -e "DATABASE_URL=mysql://my_app:secret@host.docker.internal:13306/my_app?encoding=utf8&timezone=UTC&cacheMetadata=true&quoteIdentifiers=false&persistent=false" \
  -d --rm cake-sandobox

# stop cmd example
docker stop my-cake-sandobox
```
