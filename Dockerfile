FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    curl zip unzip git

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN cp .env.example .env && php artisan key:generate

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000