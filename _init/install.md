# psa-cppv2 install on dev environment #
--------

This tutorial is going to describe the step by step installation for the psa-cppv2 global environment:

## Install OS
It is a requirement that every developper run under an unix or windoww OS, 64 bits version.

## Install Virtualbox
The project is running on a virtual environment to be production ready

### Linux ###

    aptitude install virtualbox

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

Once vagrant installed, add the NFS support if the host is Linux or Mac OS

    vagrant plugin install vagrant-bindfs
    
IMPORTANT : under windonws, this must executed before git clone

    git config --global core.autocrlf false

## Clone the front repository
In the parent directory of the current directory run

    cd [your root directory]
    git clone -b new-iteration2 https://github.com/itkg/psa-cppv2.git cppv2

NOTE : the new directory is replaced by cppv2 to avoid a vagrant bug under Windows with "-" or " " in the path

## Launch the box
Change the root directory in cppv2/_init/vagrant/puphpet/config.yaml

    replace   'D:\Documents\GitHub'

    by        '[your root directory]' 

## If something goes wrong

the vagrant up is cancelled or an error occured (no internet connection, error message in provisioning)
do, after resolution of issues 

    vagrant reload --provision
    
if all is really bad

    rm -rf [your root directory]/cppv2/_init/vagrant/.vagrant
    
retry vagrant up from zero

    vagrant up

## Launch the box
When you launch the box, it will take some time to :
Import the base box,
Launch it,
Run all the provisionning scripts

    cd cppv2/_init/vagrant
    vagrant up

## Finalize the installation
Launch the post-installation script shell

    vagrant halt
    vagrant up
    
the new vagrant up launch a complementary script in _init/vagrant/puppet/files/startup-once/install.sh

## Override the dns redirection
In the `/etc/hosts` file of your computer add the following lines :

    192.168.10.10 backend.psa-cppv2.com
    192.168.10.10 fr.psa-cppv2.com
    192.168.10.10 be.psa-cppv2.com
    192.168.10.10 cz.psa-cppv2.com
    192.168.10.10 de.psa-cppv2.com
    192.168.10.10 master.psa-cppv2.com
    192.168.10.10 media.psa-cppv2.com

## Result
The access to he backend is :

- [http://backend.psa-cppv2.com](http://backend.psa-cppv2.com)

    admin / adminAL83

The other tools have the following urls :

- Adminer : [http://192.168.10.10/adminer](http://192.168.10.10/adminer) 
- user/pwd/database => psa-cppv2/psa-cppv2/psa-cppv2

- MailCatcher : [http://192.168.10.10:1080](http://192.168.10.10:1080)

- Xhprof :  [http://192.168.10.10/xhprof/xhprof_html](http://192.168.10.10/xhprof/xhprof_html)

- Mongodb : 192.168.10.10:27017
- user/pwd/database => cppv2/cppv2/cppv2

- mongo-express : http://192.168.10.10:8081

The Root access to mysql :

    root / root
