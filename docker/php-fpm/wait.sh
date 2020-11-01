#!/usr/bin/env bash
# wait for mysql
while ! mysqladmin ping -h"mysql" -u"${DB_USER}" -p"${DB_PASSWORD}" --silent; do
  sleep 1
done

# run  scripts
pushd /var/www
  chmod 775 ./storage
  chmod 775 ./storage/logs
  php artisan migrate --force
  php artisan db:seed
  php-fpm
popd