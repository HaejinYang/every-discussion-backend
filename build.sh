source ./.env.core

rm -f ./.env

if [ "$APP_ENV" = "production" ]; then
    rm -f /usr/local/etc/php/conf.d/debug.ini
    cat ./.env.core ./.env.production > ./.env
elif [ "$APP_ENV" = "release" ]; then
        rm -f /usr/local/etc/php/conf.d/debug.ini
        cat ./.env.core ./.env.release > ./.env
else
    rm -f /usr/local/etc/php/conf.d/production.ini
    cat ./.env.core ./.env.local > ./.env
fi

composer install

php artisan migrate --force
php artisan horizon &
php artisan schedule:work &
