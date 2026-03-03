#!/bin/sh
set -e

if [ ! -d "/var/www/vendor" ]; then
    composer install
fi

# if [ -z "$APP_KEY" ]; then
#     php artisan key:generate
# fi

exec "$@"
