#!/bin/bash

if [ ! -d /var/www/default/ ]; then
  sudo mkdir /var/www/default/
fi

cd /var/www/default/

ln -sf /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/headers.load

## phpmyadmin
if [ ! -r /etc/apache2/sites-enabled/26-phpmyadmin.conf ]; then
  sudo cp /var/www/cppv2/_init/addons/26-phpmyadmin.conf /etc/apache2/sites-available/
  sudo ln -s /etc/apache2/sites-available/26-phpmyadmin.conf /etc/apache2/sites-enabled/26-phpmyadmin.conf
fi

## phpredmin
if [ ! -r /var/www/default/phpredmin ]; then
  sudo git clone https://github.com/sasanrose/phpredmin.git
fi
if [ ! -r /etc/apache2/sites-enabled/26-phpredmin.conf ]; then
  sudo cp /var/www/cppv2/_init/addons/26-phpredmin.conf /etc/apache2/sites-available/
  sudo ln -s /etc/apache2/sites-available/26-phpredmin.conf /etc/apache2/sites-enabled/26-phpredmin.conf
fi

## mongo-express
if [ ! -d /var/node_modules/mongo-express ]; then
  cd /var
  sudo npm install mongo-express
  sudo cp /var/www/cppv2/_init/addons/config.js /var/node_modules/mongo-express/config.js
  
  ## init mongo db
  mongo < /var/www/cppv2/_init/fixtures/cppv2_mongo_init.js
  
  ## fichiers de traduction
fi

if [ ! -d var/i18n/backend/ ]; then
  cd /var/www/cppv2
  tar -xvf /var/www/cppv2/_init/fixtures/i18n.tar
fi

## restart apache
sudo service apache2 restart

## vendor
if [ ! -d /var/www/cppv2/vendor ]; then
  cd /var/www/cppv2
  sudo composer update
  cd /var/www/cppv2
  sudo composer dump-autoload -o
fi

cd /var/www/cppv2/vendor
if [ ! -d /var/www/cppv2/vendor/itkg ]; then
  git clone https://github.com/itkg/itkg.git
fi
if [ ! -d /var/www/cppv2/vendor/itkg-apis ]; then
  git clone https://github.com/itkg/apis.git itkg-apis
fi


## dossiers de l'application
if [ ! -d /var/www/cppv2/public/media ]; then
  sudo mkdir -p /var/www/cppv2/public/media/image
  sudo mkdir -p /var/www/cppv2/public/media/file
  sudo mkdir -p /var/www/cppv2/public/media/video
  sudo mkdir -p /var/www/cppv2/public/media/flash
fi

if [ ! -d /var/www/cppv2/var ]; then
  sudo mkdir -p /var/www/cppv2/var/cache/application
  sudo mkdir -p /var/www/cppv2/var/cache/views
  sudo mkdir -p /var/www/cppv2/var/cache/views/frontend
  sudo mkdir -p /var/www/cppv2/var/cache/views/backend
  sudo mkdir -p /var/www/cppv2/var/logs
  sudo mkdir -p /var/www/cppv2/var/robots
  sudo mkdir -p /var/www/cppv2/var/sessions
  sudo mkdir -p /var/www/cppv2/var/import
  sudo mkdir -p /var/www/cppv2/var/export
  sudo mkdir -p /var/www/cppv2/var/cache
  sudo mkdir -p /var/www/cppv2/var/cache/mobile
  sudo mkdir -p /var/www/cppv2/var/cache/view_compiles
fi

## php-cs-fixer
if [ ! -x /usr/local/bin/php-cs-fixer ]; then
  sudo curl http://get.sensiolabs.org/php-cs-fixer.phar -o /usr/local/bin/php-cs-fixer
  sudo chmod a+x /usr/local/bin/php-cs-fixer
fi
