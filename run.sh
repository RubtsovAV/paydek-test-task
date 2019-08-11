#!/bin/bash

echo "Preparing..."
# adjust folder permissions for docker volume usage
if [ "$APP_MODE" == "development" ]; then
    find /app/storage -type d -print0 | xargs -0 chmod 777
    find /app/storage -type f -print0 | xargs -0 chmod 666
    find /app/bootstrap/cache -type d -print0 | xargs -0 chmod 777
    find /app/bootstrap/cache -type f -print0 | xargs -0 chmod 666
    find /app/vendor -type d -print0 | xargs -0 chmod 777
    find /app/vendor -type f -print0 | xargs -0 chmod 666
fi

chmod +x /app/vendor/phpunit/phpunit/phpunit
chmod +x /app/vendor/squizlabs/php_codesniffer/scripts/phpcs
chmod +x /app/vendor/squizlabs/php_codesniffer/scripts/phpcbf

cp -R /root/cron.d/* /etc/cron.d/
chown -R root:root /etc/cron.d/
chmod -R 0644 /etc/cron.d/

cp -R /root/logrotate.d/* /etc/logrotate.d/
chown -R root:root /etc/logrotate.d/
chmod -R 0644 /etc/logrotate.d/

cp -R /root/supervisor/* /etc/supervisor/conf.d
chown -R root:root /etc/supervisor/
chmod -R 0644 /etc/supervisor/

chown -R root:root ./storage/logs
chmod -R 0777 ./storage/logs

php /app/artisan migrate --force
a2enmod rewrite

if [ "$APP_MODE" == "production" ]; then
	echo "Start supervisord"
	supervisord -c /etc/supervisor/supervisord.conf
else 
	echo "Enable xdebug"
	docker-php-ext-enable xdebug
fi

cp /app/.env.common /app/.env
echo "DB_HOST=\"$DB_HOST\"" >> /app/.env
echo "DB_PORT=\"$DB_PORT\"" >> /app/.env
echo "DB_DATABASE=\"$DB_DATABASE\"" >> /app/.env
echo "DB_USERNAME=\"$DB_USERNAME\"" >> /app/.env
echo "DB_PASSWORD=\"$DB_PASSWORD\"" >> /app/.env
echo "BEST_PROXY_KEY=\"$BEST_PROXY_KEY\"" >> /app/.env
echo "GETPROXYLIST_KEY=\"$GETPROXYLIST_KEY\"" >> /app/.env

echo "Start apache2-foreground"
apache2-foreground
