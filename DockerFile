FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl zip unzip git nodejs npm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Yarn
RUN npm install -g yarn

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN yarn && yarn production

RUN php artisan config:cache
RUN php artisan route:cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=$PORT