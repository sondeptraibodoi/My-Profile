#!/usr/bin/env sh

php artisan optimize
composer dump-autoload && rm -rf public/storage && rm -rf public/mobile/storage && php artisan storage:link

chmod -R 777 public storage/ bootstrap/
chown -R www-data:www-data ./bootstrap
chown -R www-data:www-data ./storage
chown -R www-data ./public
php artisan optimize
php artisan optimize:clear
apachectl -D FOREGROUND
