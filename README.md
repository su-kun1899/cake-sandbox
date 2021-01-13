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
```

`http://localhost:8080` にブラウザからアクセス。
