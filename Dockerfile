FROM php:8.4-fpm

ARG user
ARG uid
ARG gid

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
    nodejs \
    npm \
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

RUN groupadd -g ${gid} ${user} && \
    useradd -u ${uid} -g ${user} -m ${user} && \
    usermod -p "*" ${user}

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

COPY . .

RUN chown -R ${user}:${user} /var/www

USER ${user}

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["php-fpm"]
