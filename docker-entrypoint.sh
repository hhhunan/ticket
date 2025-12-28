#!/bin/bash
set -e

echo "Настройка прав и запуск приложения..."

echo "user : $user"

chmod -R 775 /var/www/storage /var/www/bootstrap/cache
chmod -R 777 /var/www/storage/logs  # Особые права для логов

if [ -f "composer.json" ]; then
    composer install --prefer-dist --optimize-autoloader --no-interaction
fi

if [ -f "package.json" ]; then
    npm install --silent
    npm run build --silent
fi

if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    cp .env.example .env
fi

if [ -z "$(grep '^APP_KEY=' .env | grep -v '=$')" ]; then
    php artisan key:generate
fi

if [ "$DB_HOST" != "" ]; then
    until nc -z -v -w30 $DB_HOST 3306; do
      echo "Waiting for database connection..."
      sleep 5
    done

    php artisan migrate --force
fi

exec php-fpm
