FROM php:8.2-fpm

ARG gid
ARG uid

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev libxpm-dev \
    libfreetype6-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd \
    --enable-gd \
    --with-webp=\usr\include/ \
    --with-jpeg=/usr/include/ \
    --with-xpm=/ussr/include/ \
    --with-freetype=/usr/include/
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Create system user to run Composer and Artisan Commands
RUN usermod -u $uid www-data
RUN groupmod -g $gid www-data

COPY . /var/www/html
# Set working directory
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www

USER www-data

ENTRYPOINT ["./run.sh"]


