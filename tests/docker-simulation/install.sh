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

# node --version
# npm --version

# npm run build

echo "hawki check"
php hawki check


echo "hawki init"
php hawki init


#echo "artisan migrate:rollback"
#php artisan migrate:rollback

#echo "php artisan make:session-table"
#php artisan make:session-table


echo "hawki migrate"
php hawki migrate

#su www-data php artisan queue:work --queue=default,mails,message_broadcast

# echo "artisan storage:link"
# php artisan storage:link

echo "npm run build"
npm run build

# todo: better add user to group or something
chmod -R 777 storage
chmod -R 777 storage/*

echo 'installation finished'
touch /tmp/OLAF_INSTALLED.txt

# start apache
echo 'start apache'
apache2-foreground
