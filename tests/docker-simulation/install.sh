#!/bin/bash
set -e

#!/bin/bash

# Abort startup if another instance was found

if [ -e /tmp/OLAF_INSTALLED.txt ]
then
    echo "OLAF already installed"
    apache2-foreground
fi



echo "Docker entrypoint"
#set -x


echo '[
    {
        "username": "tester",
        "password": "123",
        "name": "TheTester",
        "email": "tester@MyUni.de",
        "employeetype": "Tester",
        "avatar_id": ""
    }
]' > /var/www/html/storage/app/test_users.json

composer install
npm install
npm run build

php artisan key:generate --show

# => copy key to .env in APP_KEY
echo 'create storage link'
php artisan storage:link
# RUN runuser  -u www-data php artisan storage:link

# php artisan cache:clear
# TODO: do not set 777 permissions!!!!!!!!!!
chmod -R 777 storage
chmod -R 777 storage/*
#chmod -R 775 storage
#chmod -R 775 storage/*
# composer dump-autoload

echo 'migrate database'
php artisan migrate --force
echo 'database seed'
php artisan db:seed --force

echo 'installation finished'
touch /tmp/OLAF_INSTALLED.txt


echo 'start reverb'
php artisan reverb:start &

# start apache
echo 'start apache'
apache2-foreground
