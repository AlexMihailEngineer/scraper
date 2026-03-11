FROM php:8.4-cli-alpine

# Install Postgres development dependencies
RUN apk add --no-cache postgresql-dev libpq

# Install the extension you need for Laravel + Postgres
RUN docker-php-ext-install pdo pdo_pgsql

# Install Composer so you can manage scraping packages (Guzzle, Panther, etc.)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
