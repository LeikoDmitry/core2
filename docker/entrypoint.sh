#!/bin/sh
set -e

# vendor/ не хранится в репозитории — ставим зависимости при первом запуске
if [ ! -f /var/www/html/core2/vendor/autoload.php ]; then
    composer install --working-dir=/var/www/html/core2 --no-interaction --prefer-dist
fi

# UI-тема тоже не хранится в репозитории — скачиваем из upstream
THEME_DIR=/var/www/html/core2/html/default
if [ ! -f "$THEME_DIR/model.json" ]; then
    git clone --depth 1 "${THEME_REPO:-https://github.com/easterism/core2-front-default.git}" "$THEME_DIR"
fi

mkdir -p /var/www/html/cache /var/www/html/core2/vendor/belhard/xajax/xajax_js/deferred
chown -R www-data:www-data /var/www/html/cache
chmod 0777 /var/www/html/core2/vendor/belhard/xajax/xajax_js/deferred

exec docker-php-entrypoint "$@"
