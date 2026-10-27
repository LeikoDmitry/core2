FROM php:8.2-apache

COPY --from=mlocati/php-extension-installer:2.7.0 /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:2.8.4 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/* \
    && install-php-extensions pdo_mysql zip gd intl redis memcached gearman pcntl \
    && a2enmod rewrite

COPY php.ini /usr/local/etc/php/conf.d/core2.ini
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
COPY entrypoint.sh /usr/local/bin/core2-entrypoint

RUN chmod +x /usr/local/bin/core2-entrypoint \
    && git config --system --add safe.directory '*'

WORKDIR /var/www/html

ENTRYPOINT ["core2-entrypoint"]
CMD ["apache2-foreground"]
