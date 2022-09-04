FROM php:7.4-apache

ENV APACHE2_CONF ./docker/config/apache2.conf
ENV PHP_INI ./docker/config/php.ini
ENV VHOST_FILE ./docker/config/000-default.conf

# COPY $PHP_INI "$PHP_INI_DIR/php.ini"
COPY $APACHE2_CONF /etc/apache2/apache2.conf
COPY $VHOST_FILE /etc/apache2/sites-available/000-default.conf

RUN cp /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime &&\
    echo "America/Sao_Paulo" > /etc/timezone

# Instala composer e dependencias
RUN apt-get update \
    && apt-get install -y git zlib1g-dev unzip libzip-dev wget libpq-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd pdo_mysql exif

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
    EXPECTED_COMPOSER_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)" \
    php -r "if (hash_file('sha384', 'composer-setup.php') === getenv('EXPECTED_COMPOSER_SIGNATURE')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" &&\
    php composer-setup.php --install-dir=/bin --filename=composer &&\
    php -r "unlink('composer-setup.php');"

RUN apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false wget

WORKDIR /var/www
RUN rm -Rf /var/www/html
RUN a2enmod rewrite
# chown -R www-data:www-data /var/www/storage
# chmod -R 755 /var/www/storage
