#! /bin/bash

source "${pwd}/scripts/logo.bash"

logo

[ -d "vendor" ] && echo "Directory \vendor exists. (Deleting)"
cp .env.example ./.env

rm -Rf vendor

docker exec -it emporio-php composer install --no-interaction --no-cache

docker exec -it emporio-php php artisan optimize
docker exec -it emporio-php php artisan key:generate
docker exec -it emporio-php php artisan jwt:secret

docker exec -it emporio-php php artisan optimize
docker exec -it emporio-php php artisan migrate
docker exec -it emporio-php php artisan migrate --path=database/migrations/loja
docker exec -it emporio-php php artisan db:seed

docker exec -it emporio-php php artisan optimize

docker exec -it emporio-php touch storage/logs/laravel.log
docker exec -it emporio-php chmod 777 storage/logs/laravel.log
docker exec -it emporio-php chmod -R 777 storage/framework/sessions

docker exec -it emporio-php chmod 777 storage/framework/cache
docker exec -it emporio-php chmod 777 storage/framework/views
docker exec -it emporio-php
docker exec -it emporio-php

docker exec -it emporio-php php artisan optimize

#docker exec -it emporio-php /bin/bash
exit
