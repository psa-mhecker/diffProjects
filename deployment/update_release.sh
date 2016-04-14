#!/bin/bash
# TODO check that composer is install
# NOTE : if composer is not install do : curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

if [ $# -eq 0 ]; then
	echo "Nom du tag/commit manquant"
else
	date_release=`date +"%Y%m%d%H%M"`

	mkdir -p ~/release$date_release
	cd ~/release$date_release

	echo "clone NDP Project"
	git clone  git@github.com:itkg/psa-ndp.git
	cd psa-ndp
	# FIXME ne marche que sur le dernier tag, un fois la release mergé, les anciens tag ne sont plus utilisable pour recréer un tag release_...
	git checkout -f release
	rm -rf vendor
	git merge --no-ff $1 -m  "Merge $1 sur release"

	echo "MAJ librairie externe"
	php /usr/local/bin/composer install -n

	echo "Generate Assets"
	cd assets
	npm install && bower install && grunt sprites && grunt

	echo "Suppression des .git"
	find ./vendor/* -type d -name ".git*" -exec rm -rf {} \;

	echo "Suppression des .svn"
	find ./vendor/* -type d -name ".svn*" -exec rm -rf {} \;

	echo "Ajout des vendor"
	git add vendor

	git commit -a -m "Merge release le $date_release"
	git push origin release
	git tag -a "release_$1" -m "Release $1 du $date_release"
	echo "Creation du tag termine"
	git push origin "release_$1"
	echo "Push des sources OK"
fi
echo "Fin du script"
