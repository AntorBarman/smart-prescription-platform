FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    icu-dev \
    postgresql-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    intl \
    bcmath \
    gd \
    opcache \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-req=ext-zip

# Install Node dependencies
RUN npm install

# Build frontend
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /app

# Generate APP_KEY if not set
RUN php artisan key:generate --force || true

# Create cache tables for database sessions
RUN php artisan config:cache || true

EXPOSE 8000

# Start command with migration
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]