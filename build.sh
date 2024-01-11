source ./.env.core

rm -f ./.env

if [ "$APP_ENV" = "production" ]; then
    rm -f /usr/local/etc/php/conf.d/xdebug.ini
    cat ./.env.core ./.env.production > ./.env
else
    rm -f /usr/local/etc/php/conf.d/opcache.ini
    rm -f /usr/local/etc/php/conf.d/jit.ini
    cat ./.env.core ./.env.local > ./.env
fi

composer install

php artisan migrate --force
php artisan horizon &
php artisan schedule:work &
