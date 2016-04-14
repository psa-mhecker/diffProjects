# PSA-NDP install on dev environment #
--------

This tutorial is going to describe the step by step installation for the psa-ndp global environment:

## Install OS
It is a requirement that every developper run under an unix or windoww OS, 64 bits version.

## Install Virtualbox
The project is running on a virtual environment to be production ready

### Linux ###

    aptitude install virtualbox
    apt-get install virtualbox
    
### Mac OS ###

    download http://download.virtualbox.org/virtualbox/4.3.20/VirtualBox-4.3.20-96996-OSX.dmg

### Windows ###

    download http://download.virtualbox.org/virtualbox/4.3.20/VirtualBox-4.3.20-96997-Win.exe

## Install Vagrant
We are going to use the vagrant project to manage all the vitualbox

### Linux ###

    wget https://dl.bintray.com/mitchellh/vagrant/vagrant_1.7.2_x86_64.deb
    dpkg -i vagrant_1.7.2_x86_64.deb

### Mac OS ###

    download https://dl.bintray.com/mitchellh/vagrant/vagrant_1.7.2.dmg

### Windows ###

    download https://dl.bintray.com/mitchellh/vagrant/vagrant_1.7.2.msi

IMPORTANT : under windows, 

  you must use a console emulator like cmder

    download http://gooseberrycreative.com/cmder/
  
  this must executed before git clone

    git config --global core.autocrlf false

  you must install the vagrant plugin vagrant-winnfsd because the shared folder is setup with 'nfs' type

    vagrant plugin install vagrant-winnfsd

  NOTE: nfs shared folder are fastest in almost all environment, see http://docs.vagrantup.com/v2/synced-folders/nfs.html for more details


## Clone the front repository
In the parent directory of the current directory run

    cd [your root directory]
    git clone https://github.com/itkg/psa-ndp.git
    
## Launch the box
Create a symlink to your project directory

    ln -s /your/project/dir /var/www/psa-ndp

for windows

Change the root directory in psa-npd/_init/vagrantv2/puphpet/config.yaml

replace   '/var/www/psa-ndp'

by        '[your root directory]/psa-ndp' 

## If something goes wrong

the vagrant up is cancelled or an error occured (no internet connection, error message in provisioning)
do, after resolution of issues 

    vagrant provision
    
if all is really bad

    rm -rf [your root directory]/psa-ndp/_init/vagrantv2/.vagrant

retry vagrant up from zero

    vagrant up

## Launch the box
When you launch the box, it will take some time to :
Import the base box,
Launch it,
Run all the provisionning scripts

    cd psa-ndp/_init/vagrantv2
    vagrant up

## Finalize the installation
You need to install all the php dependencies before starting the project

    vagrant ssh
    export SYMFONY__HTTP__MEDIA="http://media.psa-ndp.com"
    export SYMFONY__REDIS__CONNECTION="tcp://127.0.0.1:6379"
    sudo mkdir -p /cache
    sudo mkdir -p /logs
    sudo chmod -R 777 /cache
    sudo chmod -R 777 /logs
    cd /var/www

    ### Dev ###
    composer install

    ### Prod ###
    composer install --no-dev --no-scripts  -o
    php frontend/app/console cache:clear --env=prod


Launch the post-installation script shell

    exit
    vagrant halt
    vagrant up
    
the new vagrant up launch a complementary script in _init/vagrantv2/puppet/files/startup-once/install.sh

## Override the dns redirection
In the `/etc/hosts` file of your computer add the following lines :

    192.168.10.10 backend.psa-ndp.com
    192.168.10.10 fr.psa-ndp.com
    192.168.10.10 be.psa-ndp.com
    192.168.10.10 cz.psa-ndp.com
    192.168.10.10 de.psa-ndp.com
    192.168.10.10 master.psa-ndp.com
    192.168.10.10 media.psa-ndp.com

## Result
The access to he backend is :

- [http://backend.psa-ndp.com](http://backend.psa-ndp.com)

    admin / adminAL83

The other tools have the following urls :

- Adminer : [http://192.168.10.10/phpmyadmin](http://192.168.10.10/phpmyadmin) 
- user/pwd/database => psa-ndp/psa-ndp/psa-ndp

- MailCatcher : [http://192.168.10.10:1080](http://192.168.10.10:1080)

- Xhprof :  [http://192.168.10.10/xhprof/xhprof_html](http://192.168.10.10/xhprof/xhprof_html)

- Webgrind :  [http://192.168.10.10/webgrind](http://192.168.10.10/webgrind)

- Pimpmylog :  [http://192.168.10.10/pimpmylog](http://192.168.10.10/pimpmylog)

- Mongodb : 192.168.10.10:27017
- user/pwd/database => psa-ndp/psa-ndp/psa-ndp

The Root access to mysql :

    psa-ndp / psa-ndp

    root / root
