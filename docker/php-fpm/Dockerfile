FROM php:7.3-fpm

# Set working directory
WORKDIR /var/www/tasks

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    sqlite3 \
    libpng-dev \
    nodejs \
    npm \
    yarn \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN pecl install xdebug-beta redis
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-enable xdebug redis

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory permissions
RUN chown -R www:www /var/www

RUN export PATH=$PATH."~/.composer/vendor/bin"

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]