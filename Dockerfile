FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    curl zip unzip git nodejs npm

# Add this line - installs MySQL driver
RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN npm install -g yarn

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN yarn && yarn production

EXPOSE 10000

CMD php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT