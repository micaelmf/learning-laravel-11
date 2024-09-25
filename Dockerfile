FROM php:8.3-fpm

# Instala dependências e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    iputils-ping \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    supervisor \
    cron \
    vim \
    && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala Xdebug
# RUN pecl install xdebug \
#     && docker-php-ext-enable xdebug

# Instale a extensão do Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Instale a extensão excimer via PECL
RUN pecl install excimer \
    && docker-php-ext-enable excimer

# Configurações do Xdebug
# COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copia o arquivo de configuração do Supervisor
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Instale Node.js e npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos do projeto para o contêiner
COPY . .

# Instale as dependências do npm
RUN npm install

# Instale o Laravel Echo Server globalmente
RUN npm install -g laravel-echo-server

# Copie o arquivo de configuração do Laravel Echo Server para o contêiner
COPY laravel-echo-server.json /app/laravel-echo-server.json

# Copie o arquivo de configuração do Supervisor para o contêiner
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copie o arquivo de configuração do cron para o contêiner
COPY crontab /etc/cron.d/laravel-cron

# Ajuste as permissões do arquivo de configuração do cron
RUN chmod 0644 /etc/cron.d/laravel-cron

# Ajuste as permissões dos arquivos e diretórios
RUN chown -R www-data:www-data /var/www/html

# Ajuste as permissões do diretório de cache do npm
RUN mkdir -p /var/www/.npm && chown -R www-data:www-data /var/www/.npm

# Copie o script wait-for-it
COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

# Gere os assets do frontend fora do contêiner
# (Este passo deve ser feito manualmente antes de construir a imagem Docker)
# RUN npm run dev

# Exponha as portas que o Laravel e o Laravel Echo Server usarão
EXPOSE 8000 6001

# Comando para iniciar o Supervisor
CMD ["sh", "-c", "cron && -c /etc/supervisor/conf.d/supervisord.conf"]
