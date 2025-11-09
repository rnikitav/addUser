# Используем PHP 8.2 FPM
FROM php:8.2-fpm

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем composer файлы для кэширования зависимостей
COPY composer.json composer.lock ./

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем PHP расширения
RUN docker-php-ext-install pdo_mysql zip exif pcntl

# Устанавливаем Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Устанавливаем Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug


RUN docker-php-ext-enable redis xdebug

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создаём пользователя www
RUN groupadd -g 1000 www || true \
    && useradd -u 1000 -ms /bin/bash -g www www || true

# Копируем весь проект с правильным владельцем
COPY --chown=www:www . /var/www

# Создаём нужные папки и выставляем права
RUN mkdir -p /var/www/storage/framework/{views,cache,sessions} \
    && mkdir -p /var/www/bootstrap/cache \
    && chown -R www:www /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Переключаемся на пользователя www
USER www

# Открываем порт php-fpm
EXPOSE 9000

# Старт PHP-FPM
CMD ["php-fpm"]
