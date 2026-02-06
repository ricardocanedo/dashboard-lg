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

# Criar script de inicialização
RUN echo '#!/bin/bash\n\
    if [ -f "composer.json" ]; then\n\
    if [ ! -d "vendor" ] || [ "composer.lock" -nt "vendor/autoload.php" ]; then\n\
    echo "Instalando dependências do Composer..."\n\
    composer install --no-interaction --prefer-dist --optimize-autoloader\n\
    fi\n\
    fi\n\
    \n\
    mkdir -p storage/framework/{sessions,views,cache}\n\
    mkdir -p bootstrap/cache\n\
    chmod -R 775 storage bootstrap/cache 2>/dev/null || true\n\
    \n\
    php artisan serve --host=0.0.0.0 --port=8000\n\
    ' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

EXPOSE 8000

CMD ["/usr/local/bin/start.sh"]
