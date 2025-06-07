#!/usr/bin/env bash
set -e

# Wait for MySQL to be ready
until mysql -h db -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SHOW DATABASES;" >/dev/null 2>&1; do
  echo "Waiting for MySQL…"
  sleep 1
done

# Install dependencies & run migrations
composer install --no-interaction --optimize-autoloader
php bin/console doctrine:migrations:migrate --no-interaction

# Launch PHP-FPM
exec "$@"
