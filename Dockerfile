FROM php:8.4-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    postgresql-dev \
    libpq \
    nodejs \
    npm

# Install build dependencies (postgresql-dev), build the extension, then remove them
RUN apk add --no-cache libpq postgresql-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && apk del postgresql-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Note: If you need to build frontend assets (like Vite), you would
# typically copy your package.json and package-lock.json here, then run npm install.
# For running `npm run dev` interactively, it's often done after starting the container.

# Expose default Laravel dev server port (Vite)
EXPOSE 5173
# Expose Laravel default port if needed for direct access
EXPOSE 8000

# Default command for this image type
CMD ["php-cli"]
