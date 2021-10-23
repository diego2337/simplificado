FROM phpdockerio/php80-fpm:latest

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    php8.0-xdebug \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
#RUN docker-php-ext-install mbstring exif pcntl bcmath gd

# Configure xdebug
RUN echo "zend_extension=/usr/lib/php/20190902/xdebug.so" > /etc/php/8.0/mods-available/xdebug.ini \
        && echo "xdebug.remote_enable=on" >> /etc/php/8.0/mods-available/xdebug.ini \
        && echo "xdebug.remote_handler=dbgp" >> /etc/php/8.0/mods-available/xdebug.ini \
        && echo "xdebug.remote_port=9000" >> /etc/php/8.0/mods-available/xdebug.ini \
        && echo "xdebug.remote_autostart=on" >> /etc/php/8.0/mods-available/xdebug.ini \
        && echo "xdebug.remote_connect_back=0" >> /etc/php/8.0/mods-available/xdebug.ini \
        && echo "xdebug.idekey=docker" >> /etc/php/8.0/mods-available/xdebug.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user
