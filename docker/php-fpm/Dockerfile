FROM php:8.3-fpm

ARG USER
ARG UID
ARG WORKING_DIR

# Установим системные зависимости
RUN apt-get update && apt-get install -y \
    apt-utils git zip vim libssl-dev cron curl g++ gcc make \
    libzip-dev libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev libonig-dev libxml2-dev libmcrypt-dev

# Установим зависимости для докера
RUN docker-php-ext-install -j$(nproc) exif opcache mbstring intl pdo pdo_mysql sockets zip bcmath soap \
    && docker-php-source delete \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd

# Удалим не нужные пакеты
RUN apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y

# Установим Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создаем системного пользователя для запуска composer и artisan команд
RUN useradd -G www-data,root -u $UID -d /home/$USER $USER
RUN mkdir -p /home/$USER/.composer && \
    chown -R $USER:$USER /home/$USER

# Установим рабочую директорию
WORKDIR $WORKING_DIR

USER $USER

EXPOSE 9000
CMD ["php-fpm"]
