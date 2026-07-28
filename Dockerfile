FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo_sqlite bcmath \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN npm ci && npm run build

RUN composer dump-autoload --optimize

EXPOSE 8000

CMD cp .env.example .env && sed -i '/^APP_KEY=/d' .env && echo APP_KEY=$APP_KEY >> .env && touch database/database.sqlite && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=$PORT
