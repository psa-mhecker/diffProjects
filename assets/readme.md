# NDP Front-end

Ce projet héberge tous les développement front-end du projet NDP.

## Arborescence

    _ assets  (Répertoire de base)
     |_ css
     |_ fonts 
     |_ img
     |_ js
     |_ doc (Documentation du projet)

## Install

### nodeJS

```
curl -sL https://deb.nodesource.com/setup_5.x | sudo bash -
sudo apt-get install nodejs
```

### ruby 2

```
sudo apt-get install software-properties-common python-software-properties
sudo apt-add-repository ppa:brightbox/ruby-ng
sudo apt-get update
sudo apt-get install -y ruby2.0 ruby2.0-dev ruby2.0-doc
```

### Sass

```
sudo gem update --system
sudo gem install sass
sudo gem install scss-lint
```

### Grunt

```
sudo npm install -g grunt-cli bower
```

## Lancement du projet

Se placer à la racine du répertoire `assets` et lancer la commande

```
npm install && bower install
```
Cette commande est à lancer uniquement dans le cadre du projet natif NDP (non confishow)
Génération de la sprite d'images et de la css associée `_new_sprites.scss`

```
grunt sprites
```

build the js, css and copy img and fonts in distribuable directory backend/public/media/design/frontend/[deviceTarget]

```
grunt build
```

Pour entamer un processus de dev lancer

```
grunt dev
```
Pour travailler dans patternLab lancer

```
grunt patterndev
```

* [Commandes à utiliser dans la VM](https://github.com/itkg/psa-ndp/wiki/FRONT-END#commandes-à-utiliser-dans-la-vm)
* [Mise à jour de l'application](https://github.com/itkg/psa-ndp/wiki/FRONT-END#mise-à-jour-de-lapplication)
* [URL D’ACCÈS AU PROJET](https://github.com/itkg/psa-ndp/wiki/FRONT-END#url-daccès-au-projet)


## Les information concernant le système de test sont situé dans :
documentation/testing-system.md

