#! /bin/bash

source "${pwd}/scripts/logo.bash"

configure_command_install()
{
    logo

    out "<33>Copying .env File"
    cp .env.example ./.env

    [ -d "vendor" ] && out "Directory /vendor exists. (Deleting)"
    [ -d "vendor" ] && docker exec -it emporio-php rm -Rf vendor

    out "<33>Installing Composer"
    docker exec -it emporio-php composer install --no-interaction --no-cache

    out ""
    out "<33>Generating Keys"
    docker exec -it emporio-php php artisan optimize
    docker exec -it emporio-php php artisan key:generate
    docker exec -it emporio-php php artisan jwt:secret

    out ""
    out "<33>Migrating and Seeding Datatables"
    docker exec -it emporio-php php artisan migrate
    docker exec -it emporio-php php artisan migrate --path=database/migrations/loja
    docker exec -it emporio-php php artisan db:seed

    out ""
    out "<33>Permissions to Local Storages"
    docker exec -it emporio-php touch storage/logs/laravel.log
    docker exec -it emporio-php chmod 777 storage/logs/laravel.log
    docker exec -it emporio-php chmod -R 777 storage/framework/sessions
    docker exec -it emporio-php chmod 777 storage/framework/cache
    docker exec -it emporio-php chmod 777 storage/framework/views

    docker exec -it emporio-php php artisan optimize

    out ""
}
