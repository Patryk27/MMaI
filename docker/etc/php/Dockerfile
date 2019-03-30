FROM php:7.3-fpm

# Create an unprivileged user
RUN groupadd -g 1000 user && useradd -m -r -u 1000 -g user user

# Install system dependencies
RUN apt-get update && apt-get install -y g++ git libghc-zlib-dev libicu-dev libpq-dev libzip-dev nano unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) iconv intl pdo pdo_pgsql zip

# Switch to the unprivileged user
USER user

# Install nvm
RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | bash && \
    export NVM_DIR="$HOME/.nvm" && [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" && \
    nvm install 10 && nvm use 10

# Set-up 'lv' alias for Laravel's Artisan
RUN echo 'alias lv="php artisan"' >> ~/.bashrc

WORKDIR /var/www/html
