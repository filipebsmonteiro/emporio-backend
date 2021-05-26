#! /bin/bash

pwd=$(pwd)

cd pwd/..

#[ -d "/vendor/" ] && echo "Directory /vendor/ exists."
cp .env.example ./.env

rm -Rf vendor

docker exec -it app composer install --no-interaction --no-cache

docker exec -it app php artisan optimize
docker exec -it app php artisan key:generate
docker exec -it app php artisan jwt:secret

docker exec -it app php artisan optimize
docker exec -it app php artisan migrate
docker exec -it app php artisan migrate --path=database/migrations/loja
docker exec -it app php artisan db:seed

docker exec -it app php artisan optimize

docker exec -it app /bin/bash
exit

#php artisan serve --host=0.0.0.0
