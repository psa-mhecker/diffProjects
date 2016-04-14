#!/bin/ksh

# Script de build NDP
# Basé sur https://github.com/itkg/psa-ndp/blob/develop/deployment/deploy.yml (bd62ee5)
##### Configuration du script
# Env pour le BO
export TYPE_ENVIRONNEMENT=PSA_INTEGRATIONGIT
# Env pour le FO
export SYMFONY_ENV=intgit
# BD a sauvegarder
export DBNAME=NDP_INTGIT
# Chemin des exe utilisé
export EXE_GIT=/users/ndp00/modules/git/bin/git
export EXE_COMPOSER=/users/login/mdendp00/bin/composer
export EXE_PHP=/users/login/mdendp00/bin/php.sh
export EXE_REDIS=/soft/red/bin/redis-cli
export EXE_NPM=/soft/njs02/lib/node_modules/npm/bin/npm-cli.js
export EXE_GRUNT=/soft/njs02/lib/node_modules/grunt-cli/bin/grunt
export EXE_BOWER=/soft/njs02/lib/node_modules/bower/bin/bower
export EXE_NODE=/soft/njs02/bin/node
export EXE_SASS=/users/login/mdendp00/bin/gem/sass/bin/sass
# Config du projet
export GIT_PROJECT="https://psa-jforestier:jemore1975@github.com/itkg/psa-ndp"
# Proxy
export HTTP_PROXY=http://mdendp00:rcpel8z6@relaishttp.sgppsa.com
export HTTPS_PROXY=http://mdendp00:rcpel8z6@relaishttp.sgppsa.com
export http_proxy=$HTTP_PROXY
export https_proxy=$HTTPS_PROXY
# Chemin du cache pour le FO (utilisÃ© lors du composer)
export FRONTEND_VAR_PATH=/users/login/mdendp00/web/html/integration.git/var/frontend
# URL du media (utilisÃ© lors du composer)
export SYMFONY__HTTP__MEDIA="http://media.ndp.git.inetpsa.com"
# Chaine de connexion Ã  REDIS (utilisÃ© lors du composer)
export SYMFONY__REDIS__CONNECTION="tcp://yval1ea0.inetpsa.com:6379?database=0"

echo ===============================================================
date
echo "Ce script va mettre à jour l'INTEGRATION en provenance de GIT"

red=`tput setaf 1` 2>/dev/null
green=`tput setaf 2` 2>/dev/null
yellow=`tput setaf 3` 2>/dev/null
blue=`tput setaf 4` 2>/dev/null
reset=`tput sgr0` 2>/dev/null

show_help() {
cat << EOF
Usage: ${0##*/} [-f] [-v] [-h]
Update the current directory with code from the Git repository.
  -f : force update
  -v : verbose
  --no-composer : do not make composer install
  --no-doctrine : do not make doctrine migration
  --no-build    : do not build the css/js (grunt, bower)
  --no-package  : do not create diff package
Available tools are :
  git      : $EXE_GIT
  composer : $EXE_COMPOSER
  php      : $EXE_PHP
  redis    : $EXE_REDIS
  npm      : $EXE_NPM
  grunt    : $EXE_GRUNT
  bower    : $EXE_BOWER

EOF
}

LOG_INFO () {
#$1 : info string
	if [ "$VERBOSE" == "1" ]; then
		echo "${yellow}INFO  : $1 ${reset}"
	fi
}
LOG_FATAL () {
#$1 : fail string
#$2 : error code
	echo "${red}FATAL : $1 ${reset}"
	exit $2
}

LOG_GREEN () {
	echo "${green}$1 ${reset}"
}

export PATH=$PATH:/psa/commun/adminsys/bin:$(dirname $EXE_NODE):$(dirname $EXE_SASS):$(dirname $EXE_GIT)
export FORCE=0
export VERBOSE=0
export NO_COMPOSER=0
export NO_DOCTRINE=0
export NO_BUILD=0
export NO_PACKAGE=0
OPTIND=1 # Reset is necessary if getopts was used previously in the script.  It is a good idea to make this local in a function.
while getopts "fvh-:" opt; do
    case "$opt" in		
        h)
            show_help
            exit 0
            ;;
		'?')
            show_help >&2
            exit 1
            ;;
		'-')
			case "${OPTARG}" in
                no-composer)
					NO_COMPOSER=1
					;;
				no-doctrine)
					NO_DOCTRINE=1
					;;
				no-build)
					NO_BUILD=1
					;;
				no-package)
				    NO_PACKAGE=1
					;;
				*)
					show_help
					exit 0
					;;
			esac;;
        f)  
			FORCE=1
            ;;
		v)
			VERBOSE=1
			;;
		
        '?')
            show_help >&2
            exit 1
            ;;
    esac
done


if [ "$VERBOSE" == "1" ]; then
	LOG_INFO "Mode verbose activé."
	if [ "$NO_COMPOSER" == "1" ]; then
		LOG_INFO "  Composer ne sera pas utilisé"
	fi
	if [ "$NO_DOCTRINE" == "1" ]; then
		LOG_INFO "  Doctrine ne sera pas utilisé"
	fi
fi
# On se place dans le rep d'execution de ce script
# Cela evite les dysfonctionnements de cron
cd $(dirname $(readlink -f $0))
LOG_INFO "Répertoire de travail : $(pwd)"


# Quelques vérification
tmp=$($EXE_GIT config -l | grep "http.proxy" | wc -l)
if [ "$tmp" == "0" ]; then
	LOG_FATAL "La commande \"git config -l\" indique qu'il n'y a pas de proxy configuré pour GIT." \
		1	
fi

echo "===== \"Clone\" du projet :"
$EXE_GIT clone $GIT_PROJECT .  > /dev/null 2>&1
return_code=$?
if [ "$return_code" == "128" ]; then
	LOG_INFO "La commande \"git clone\" indique qu'un clone est déja présent. On passe à la mise à jour."
fi
echo "  => Clone terminé".

PRJ_CURRENT_VERSION=$($EXE_GIT rev-parse HEAD)
echo "Version actuelle du projet     : $PRJ_CURRENT_VERSION"

PRJ_NEXT_VERSION=$($EXE_GIT ls-remote origin -h develop | cut -c-40)
echo "Version disponible sur le repo : $PRJ_NEXT_VERSION"

if [ "$PRJ_CURRENT_VERSION" == "$PRJ_NEXT_VERSION" ]; then
	LOG_GREEN "Il n'est pas nécessaire de mettre à jour le projet."
	if [ "$FORCE" == "0" ]; then
		LOG_GREEN "Utilisez -f au script pour forcer les mises à jour."
		exit 0;
	else
		LOG_GREEN "Option -f utilisé, on force les mises à jour."
	fi
fi


echo "===== \"Pull\" du projet (mise à jour à la dernière version a partir de la version $PRJ_CURRENT_VERSION) :"

$EXE_GIT checkout assets
$EXE_GIT pull > /tmp/git-pull-result.tmp
return_code=$?
if [ "$return_code" != "0" ]; then
	LOG_FATAL "  ERREUR lors de la commande \"git pull\". Code retour = $return_code." \
		1
fi
LOG_INFO "Résultat commande pull : $(cat /tmp/git-pull-result.tmp)"
UPTODATE=$(grep "up-to-date" /tmp/git-pull-result.tmp | wc -l)
PRJ_PREVISIOUS_VERSION=$PRJ_CURRENT_VERSION
PRJ_CURRENT_VERSION=$($EXE_GIT rev-parse HEAD)

if [ "$UPTODATE" == "1" ]; then
	LOG_GREEN "Le projet est déja à jour."
	if [ "$FORCE" == "0" ]; then
		exit 0;
	fi
else
	date >> install_from_git.log
	echo "Upgrade from version $PRJ_PREVISIOUS_VERSION to $PRJ_CURRENT_VERSION" >> install_from_git.log
	cat /tmp/git-pull-result.tmp >> install_from_git.log
fi
LOG_GREEN "Nouvelle version du projet installée : $PRJ_CURRENT_VERSION"

if [ "$NO_PACKAGE" == "0" ]; then
	PACKAGE=./livraisons/$(date +\%Y-\%m-\%d)
	LOG_INFO "Création du package de livraison dans $PACKAGE"
	./PSA_package_int.sh -v -f $PRJ_PREVISIOUS_VERSION -t $PRJ_CURRENT_VERSION -p $PACKAGE
fi

LOG_INFO "Création des répertoires supplémentaire du projet"

mkdir $FRONTEND_VAR_PATH > /dev/null 2>&1
mkdir $FRONTEND_VAR_PATH/../../logs > /dev/null 2>&1

op chmod -R 777 $FRONTEND_VAR_PATH/..
op chmod -R 777 $FRONTEND_VAR_PATH/../../logs
echo "===== Lancement de \"composer\""
if [ "$NO_COMPOSER" == "1" ]; then
	echo "  Composer non utilisé"
else
	if [ "$VERBOSE" == "1" ]; then
		extra=--verbose
	fi
	$EXE_COMPOSER install -o --no-interaction $extra
	return_code=$?
	if [ "$return_code" != "0" ]; then
		LOG_FATAL "  ERREUR lors de la commande \"composer install\". Code retour = $return_code" \
			1
	fi
fi

# Update install.log with current updated version
echo $(date) Update code to version $PRJ_CURRENT_VERSION >> ./install.log

# Vérification de la conf
LOG_INFO "Vérification de la configuration des répertoires"
if [ ! -d nfs ]; then
	LOG_FATAL "  ERREUR : Répertoire nfs manquant. Il faut récupérer une version conforme a l'environnement" \
		2
fi
if [ ! -d nfs/const ]; then
	LOG_FATAL "  ERREUR : Répertoire nfs/const manquant. Il faut récupérer une version conforme a l'environnement" \
		2
fi
if [ ! -d nfs/var ]; then
	LOG_FATAL "  ERREUR : Répertoire nfs/var manquant. Il faut récupérer une version conforme a l'environnement" \
		2
fi
if [ ! -e .httpd_common ]; then
	LOG_FATAL "  ERREUR : Fichier .httpd_common manquant. Refaire le lien symbolique." \
		2
fi
if [ ! -e backend/.httpd ]; then
	LOG_FATAL "  ERREUR : Fichier backend/.httpd manquant. Refaire le lien symbolique." \
		2
fi
if [ ! -e frontend/.httpd ]; then
	LOG_FATAL "  ERREUR : Fichier frontend/.httpd manquant. Refaire le lien symbolique." \
		2
fi
if [ ! -e frontend/.httpd_dcr ]; then
	LOG_FATAL "  ERREUR : Fichier frontend/.httpd_dcr manquant. Refaire le lien symbolique." \
		2
fi
if [ ! -e mysqlnd_ms_plugin.ini ]; then
	LOG_FATAL "  ERREUR : Fichier mysqlnd_ms_plugin.ini manquant. Refaire le lien symbolique." \
		2
fi
if [ ! -e php-console.ini ]; then
	LOG_FATAL "  ERREUR : Fichier php-console.ini manquant. Refaire le lien symbolique." \
		2
fi
mkdir -p nfs/var/sessions
op chmod -R 777 nfs/var/sessions/

echo "===== Mise à jour de la base de données"
if [ "$NO_DOCTRINE" == "1" ]; then
	echo "  Doctrine non utilisé"
else
	cd frontend
	LOG_INFO "Répertoire de travail : $(pwd)"
	LOG_INFO "Lancement du statut des migration doctrine"
	$EXE_PHP app/console doctrine:migration:status --env=$SYMFONY_ENV > /tmp/git-doctrine.tmp
	return_code=$?
	LOG_INFO "$(cat /tmp/git-doctrine.tmp)"
	if [ "$return_code" != "0" ]; then	
		LOG_FATAL "  ERREUR lors de la commande \"doctrine:migration:status\". Code retour = $return_code" \
			3
	fi
	UPTODATE=$(grep "Already at latest version" /tmp/git-doctrine.tmp | wc -l)
	if [ "$UPTODATE" == "1" ]; then
		LOG_GREEN "La base de données est déja à jour"
	else
		
		echo "  INFO : des migrations doivent avoir lieu. Sauvegarde de la base :"		
		/users/login/mdendp00/bin/backupdb-auto.sh -d $DBNAME -z gz -p /users/login/mdendp00/web/html/tools/backup/git -v
		echo $(date) Databse $DBNAME is backup in /users/login/mdendp00/web/html/tools/backup/git >> ../install.log
		echo "  INFO : lancement de la migration"
		$EXE_PHP app/console doctrine:migration:migrate --no-interaction --env=$SYMFONY_ENV
		return_code=$?
		if [ "$return_code" != "0" ]; then
			LOG_FATAL "  ERREUR lors de la commande \"doctrine:migration:migrate\". Code retour = $return_code" \
				3
		fi
		echo $(date) Update database to version $PRJ_CURRENT_VERSION >> ../install.log
		LOG_GREEN "La base de données est maintenant à jour à la version $PRJ_CURRENT_VERSION"
	fi
	cd ..
fi

echo "==== Execution des script Symfony"
cd frontend
$EXE_PHP app/console psa:translation:init --env=$SYMFONY_ENV
$EXE_PHP app/console psa:generate:sitemap --env=$SYMFONY_ENV
cd ..

echo "==== Build du projet (npm, bower, grunt)"
if [ "NO_BUILD" == "1" ]; then
	echo "  Build non utilisé"
else
	cd assets
	# npm install
	LOG_INFO "Répertoire de travail : $(pwd)"
	LOG_INFO "Commande nom install"
	$EXE_NPM install
	return_code=$?
	if [ "$return_code" != "0" ]; then
		LOG_FATAL "  ERREUR lors de la commande \"npm install\". Code retour = $return_code" \
			4
	fi
	# bower install
	LOG_INFO "Commande bower install"
	$EXE_BOWER install 
	return_code=$?
	if [ "$return_code" != "0" ]; then
		LOG_FATAL "  ERREUR lors de la commande \"bower install\". Code retour = $return_code" \
			5
	fi
	# grunt sprites
	#LOG_INFO "Commande grunt sprites"
	#$EXE_GRUNT sprites 
	#return_code=$?
	#if [ "$return_code" != "0" ]; then
	#	LOG_FATAL "  ERREUR lors de la commande \"grunt sprites\". Code retour = $return_code" \
	#		5
	#fi
	# grunt
	LOG_INFO "Commande grunt"
	$EXE_GRUNT build
	return_code=$?
	if [ "$return_code" != "0" ]; then
		LOG_FATAL "  ERREUR lors de la commande \"grunt\". Code retour = $return_code" \
			5
	fi
	# On s'occupe de Patternlab	
	cd patternlab
	LOG_INFO "Build de patternlab. Répertoire de travail : $(pwd)"
	LOG_INFO "Commande grunt"
	$EXE_NPM install
	$EXE_GRUNT
	return_code=$?
	if [ "$return_code" != "0" ]; then
		LOG_FATAL "  ERREUR lors de la commande \"grunt\". Code retour = $return_code" \
			5
	fi
	cd ..
	LOG_GREEN "Build OK"
	cd ..
fi



echo "==== Effacement cache REDIS"
$EXE_REDIS flushall

echo "==== Restart Apache : TODO"

op chmod -R 777 $FRONTEND_VAR_PATH/..
LOG_GREEN "${date} Fin du script"
