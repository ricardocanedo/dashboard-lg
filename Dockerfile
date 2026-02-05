FROM php:7.4-cli

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto
COPY . .

# Instalar Laravel se não existir
RUN if [ ! -f "artisan" ]; then \
    rm -rf /var/www/* /var/www/.[!.]* && \
    composer create-project --prefer-dist laravel/laravel:^7.0 . && \
    cp .env.example .env; \
    elif [ -f "composer.json" ]; then \
    composer install --no-interaction --prefer-dist --optimize-autoloader; \
    fi

# Copiar .env.example do host para dentro do container (sobrescrever o padrão do Laravel)
COPY .env.example /var/www/.env.example

# Criar diretórios necessários e permissões
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    mkdir -p bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
