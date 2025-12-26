FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    netcat-openbsd \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

#Copy existing application files
COPY . /var/www

# Run post-install steps
#RUN php artisan key:generate --show
#RUN php artisan config:cache
#RUN php artisan route:cache

# Set proper permissions for Laravel storage and cache directories
#RUN chown -R www-data:www-data storage bootstrap/cache

# Fix permissions (image-level only)
#RUN chown -R www-data:www-data /var/www \
#    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Switch user (MUST be separate)
#USER www-data

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
