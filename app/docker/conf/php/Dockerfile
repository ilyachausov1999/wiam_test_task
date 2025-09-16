FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        dpkg-dev \
        libpng-dev \
        libjpeg-dev \
        libonig-dev \
        libxml2-dev \
        libpq-dev \
        libzip-dev \
        zip \
        unzip \
        libmagickwand-dev \
        libssh2-1-dev \
        cron

RUN pecl install imagick
RUN docker-php-ext-enable imagick

RUN pecl install ssh2-1.3.1
RUN docker-php-ext-enable ssh2

RUN docker-php-ext-configure gd \
  --enable-gd \
  --with-jpeg

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd sockets zip

# Get Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]