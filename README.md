# cake-sandbox

## Quick Start Guide

```sh
docker-compose up --build
```

`http://localhost:8080` にブラウザからアクセス。

## Create CakePHP project

```sh
docker exec cake-sandbox_web_1 \
  composer create-project \
        --no-interaction \
        --working-dir=/var/www \
        --prefer-dist \
        cakephp/app:4.* \
        cake-sandbox
```
