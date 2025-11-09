#!/bin/bash
set -e

# Переключаемся в рабочую директорию
cd /var/www

cp .env.example .env
echo ".env создан из .env.example"

# Устанавливаем зависимости composer
composer install --no-interaction --optimize-autoloader

# Генерируем APP_KEY если не установлен
if ! php artisan key:show >/dev/null 2>&1; then
    php artisan key:generate
fi

# Выполняем миграции
php artisan migrate --force

# Запуск основного процесса (php-fpm)
exec "$@"
