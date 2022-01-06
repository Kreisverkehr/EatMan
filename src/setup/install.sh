#!/bin/sh

# install required packages
sudo apt update
sudo apt install git apache2 mysql-server php php-mysqli net-tools -y

# clone repo
cd /opt
sudo git clone https://github.com/Kreisverkehr/EatMan.git
sudo chmod -R 777 EatMan/
cd EatMan/

# link to html directory
sudo ln -s /opt/EatMan/src/wwwroot/ /var/www/html/EatMan

# create database
cd src/setup/
PASSWDDB="$(openssl rand -base64 12)"
MAINDB="EatMan"
sudo mysql -e "CREATE DATABASE ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
sudo mysql -e "CREATE USER ${MAINDB}@localhost IDENTIFIED BY '${PASSWDDB}';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ${MAINDB}.* TO '${MAINDB}'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
sudo mysql EatMan < createdb.sql
cd ../../

# install composer
cd src/wwwroot/
EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
then
    >&2 echo 'ERROR: Invalid installer checksum'
    rm composer-setup.php
    exit 1
fi
php composer-setup.php --quiet
RESULT=$?
rm composer-setup.php
cd ../../

# create settings
cd src/wwwroot/sys/
echo "<?php" > settings.php
echo "\$db_host = \"localhost\";" >> settings.php
echo "\$db_user = \"$MAINDB\";" >> settings.php
echo "\$db_pass = \"$PASSWDDB\";" >> settings.php
cd ../../../

# install dependencies
cd src/wwwroot/
php composer.phar install
cd ../../

# DONE!
PRIVATEIP=$(hostname -I | awk '{print $1}')
echo "EatMan is installed. Navigate to http://$PRIVATEIP/EatMan/ and start feeding it with your favorite dishes.";