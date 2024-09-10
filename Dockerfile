FROM php:8.3-fpm

# Instala dependências e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    supervisor

# Instala extensões PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configurações do Xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copia o arquivo de configuração do Supervisor
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf
