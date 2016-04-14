#!/bin/bash

if [ ! -d /var/www/default/ ]; then
  sudo mkdir /var/www/default/
fi

cd /var/www/default/

## phpmyadmin
if [ ! -d /var/www/default/phpmyadmin ]; then
  sudo ln -s /usr/share/phpmyadmin /var/www/default/phpmyadmin
fi
if [ ! -r /etc/apache2/sites-enabled/24-phpmyadmin.conf ]; then
  sudo cp /var/www/_init/addons/24-phpmyadmin.conf /etc/apache2/sites-available/
  sudo ln -s /etc/apache2/sites-available/24-phpmyadmin.conf /etc/apache2/sites-enabled/24-phpmyadmin.conf
fi

## phpredmin
if [ ! -r /var/www/default/phpredmin ]; then
  sudo git clone https://github.com/sasanrose/phpredmin.git
  sudo cp /var/www/_init/addons/25-phpredmin.conf /etc/apache2/sites-available/
  sudo ln -s /etc/apache2/sites-available/25-phpredmin.conf /etc/apache2/sites-enabled/25-phpredmin.conf
fi
if [ ! -r /etc/apache2/sites-enabled/25-phpredmin.conf ]; then
  sudo cp /var/www/_init/addons/25-phpredmin.conf /etc/apache2/sites-available/
  sudo ln -s /etc/apache2/sites-available/25-phpredmin.conf /etc/apache2/sites-enabled/25-phpredmin.conf
fi

# @todo session.save_handler: redis
# @todo session.save_path: 'tcp://localhost:6379'

## restart apache
sudo service apache2 restart

## vendor
if [ -r /var/www/frontend/composer.json ]; then
  cd /var/www/frontend
  sudo composer update
  #sudo composer dump-autoload -o
fi
if [ -r /var/www/backend/composer.json ]; then
  cd /var/www/backend
  sudo composer update
  #sudo composer dump-autoload -o
fi
if [ -r /var/www/composer.json ]; then
  cd /var/www
  sudo composer update
  #sudo composer dump-autoload -o
fi

## reset du git
#tar -cvf _init/local.tar _init/vagrant/puphpet/config.yaml
#tar -xvf _init/local.tar

## fichiers de traduction
#tar -xvf _init/fixtures/i18n.tar

## dossiers de l'application
sudo mkdir -p /var/www/backend/public/media
sudo mkdir -p /var/www/backend/public/media/image
sudo mkdir -p /var/www/backend/public/media/file
sudo mkdir -p /var/www/backend/public/media/video
sudo mkdir -p /var/www/backend/public/media/flash
sudo mkdir -p /var/www/backend/var/cache/application
sudo mkdir -p /var/www/backend/var/cache/views
sudo mkdir -p /var/www/backend/var/cache/views/frontend
sudo mkdir -p /var/www/backend/var/cache/views/backend
sudo mkdir -p /var/www/backend/var/logs
sudo mkdir -p /var/www/backend/var/robots
sudo mkdir -p /var/www/backend/var/sessions
sudo mkdir -p /var/www/backend/var/import
sudo mkdir -p /var/www/backend/var/export
sudo mkdir -p /var/www/backend/var/cache
sudo mkdir -p /var/www/backend/var/cache/mobile
sudo mkdir -p /var/www/backend/var/cache/view_compiles

## php-cs-fixer
sudo curl http://get.sensiolabs.org/php-cs-fixer.phar -o /usr/local/bin/php-cs-fixer
sudo chmod a+x /usr/local/bin/php-cs-fixer