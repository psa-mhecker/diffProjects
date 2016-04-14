#!/bin/bash

export SYMFONY__HTTP__MEDIA="http://media.psa-ndp.com"
export SYMFONY__REDIS__CONNECTION="tcp://127.0.0.1:6379"


if [ ! -d /var/www/default/ ]; then
  sudo mkdir /var/www/default/
fi

cd /var/www/default/

## phpmyadmin
if [ ! -r /etc/apache2/sites-enabled/26-phpmyadmin.conf ]; then
  sudo cp /var/www/_init/addons/26-phpmyadmin.conf /etc/apache2/sites-available/
  sudo ln -s /etc/apache2/sites-available/26-phpmyadmin.conf /etc/apache2/sites-enabled/26-phpmyadmin.conf
  #mysql -uroot -proot psa-ndp <  /var/www/_init/fixtures/psa-ndp-views.sql
fi

## phpredmin
if [ ! -r /var/www/default/phpredmin ]; then
  cd /var/www/default/
  sudo git clone https://github.com/sasanrose/phpredmin.git
fi
if [ ! -r /etc/apache2/sites-enabled/26-phpredmin.conf ]; then
  sudo cp /var/www/_init/addons/26-phpredmin.conf /etc/apache2/sites-available/
  sudo ln -s /etc/apache2/sites-available/26-phpredmin.conf /etc/apache2/sites-enabled/26-phpredmin.conf
fi

## webgrind
if [ ! -r /var/www/default/webgrind ]; then
  cd /var/www/default
  wget https://webgrind.googlecode.com/files/webgrind-release-1.0.zip
  unzip webgrind-release-1.0.zip
  rm -f webgrind-release-1.0.zip
fi
if [ ! -r /etc/apache2/sites-enabled/26-webgrind.conf ]; then
  sudo cp /var/www/_init/addons/26-webgrind.conf /etc/apache2/sites-available/
  sudo ln -s /etc/apache2/sites-available/26-webgrind.conf /etc/apache2/sites-enabled/26-webgrind.conf
fi

# @todo session.save_handler: redis
# @todo session.save_path: 'tcp://localhost:6379'

## restart apache
sudo service apache2 restart

## vendor
#cd /var/www
#if [ -r /var/www/composer.json ]; then
#  sudo composer install
#fi

## dossiers de l'application
if [ ! -d /var/www/backend/public/media/image ]; then
  sudo mkdir -p /var/www/backend/public/media/image
  sudo mkdir -p /var/www/backend/public/media/file
  sudo mkdir -p /var/www/backend/public/media/video
  sudo mkdir -p /var/www/backend/public/media/flash
  cd /var/www/backend/public/media
  sudo tar -xzvf /var/www/_init/fixtures/images.tar.gz
fi

if [ ! -d /var/backend/i18n ]; then
  sudo mkdir -p /var/backend/i18n
  sudo chown -R www-data /var/backend/i18n
  cd /var/backend
  sudo tar -xzvf /var/www/_init/fixtures/i18n.tar.gz
fi

#if [ -d /var/www/backend/var ]; then
#  sudo rm -rf /var/www/backend/var
#fi

cd /var/www

sudo mkdir -p /var/www/var/wurfl
sudo chmod -R 777 /var/www/var

#new version
#sudo mkdir -p /cache
#sudo mkdir -p /logs
sudo mkdir -p /var/frontend/cache
sudo mkdir -p /var/frontend/logs
sudo mkdir -p /var/backend/cache
sudo mkdir -p /var/backend/logs

#sudo mkdir -p /var/backend/i18n
sudo setfacl -R -m u:"www-data":rwX -m u:`whoami`:rwX /var/frontend/cache
sudo setfacl -R -m u:"www-data":rwX -m u:`whoami`:rwX /var/frontend/logs
sudo setfacl -R -m u:"www-data":rwX -m u:`whoami`:rwX /var/backend/cache
sudo setfacl -R -m u:"www-data":rwX -m u:`whoami`:rwX /var/backend/logs
#sudo setfacl -R -m u:"www-data":rwX -m u:`whoami`:rwX /cache/
#sudo setfacl -R -m u:"www-data":rwX -m u:`whoami`:rwX /logs/
#sudo chown -R www-data /var/backend/i18n

# Wurfl correct directory structure for XML database file access and mobile cache files
sudo mkdir -p /var/wurfl/cache/mobile
sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx /var/wurfl/cache/mobile
sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx /var/wurfl/cache/mobile
sudo ln -s /var/www/backend/application/configs/Wurfl/*/wurfl.xml /var/wurfl/

# A supprimer
sudo chmod -R 777 /var/frontend
sudo chmod -R 777 /var/backend

## php-cs-fixer
if [ ! -x /usr/local/bin/php-cs-fixer ]; then
  sudo curl http://get.sensiolabs.org/php-cs-fixer.phar -o /usr/local/bin/php-cs-fixer
  sudo chmod a+x /usr/local/bin/php-cs-fixer
fi

cd /var/www/frontend
php app/console doctrine:migration:migrate
