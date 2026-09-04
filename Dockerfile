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

# Ensure storage and cache directories exist and are writable
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /app

EXPOSE 8000

# Clear cache, migrate, seed roles, activate users, start server
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && php artisan db:seed --class=RolePermissionSeeder --force && php artisan tinker --execute=\"App\\Models\\User::query()->update(['status' => 'active', 'email_verified_at' => now()]);\" && php artisan serve --host=0.0.0.0 --port=8000"]