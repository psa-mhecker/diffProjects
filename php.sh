#!/bin/sh
# Lanceur de script PHP.
# Il remplace un appel direct � l'executable PHP, et positionne certains
# parametres de PHP : fichier .ini, timezone, variables d'env.
# Un fichier "php.ini" doit �tre pr�sent au m�me endroit que ce lanceur.
# Ce lanceur peut etre utilis� sur un env de DEV ou en PROD.

# V�rification si un fichier php.ini est bien pr�sent 
BASEDIR=$(dirname $(readlink -f $0))
if [ ! -e $BASEDIR/php-console.ini ] ; then
	echo Erreur : un fichier php-console.ini doit se trouver dans $BASEDIR >&2
	exit 1
fi

# Fix du warning php "default.timezone"
if [ "$TZ" == "" ] ; then
	export TZ=Europe/Paris
fi

# Sur les serveurs de PREPROD et de PROD on peut utiliser des var $UNX*
# fichier de conf et des script PHP : $UNXEXDATA
# rep log : $UNXLOG
# rep temp : $UNXTMP
# rep des sh : $UNXEXSCRIPT
# rep de l'appli web : $UNXWEB=/users/cpw00/web (rajouter /nfs/html/ pour arriver au rep de l'application sur la baie

if [ "$UNXEXDATA" == "" ]
then
	# env DEV
	export UNXEXDATA=$BASEDIR/
	export UNXLOG=$BASEDIR/logs
	export UNXTMP=/tmp
	export UNXEXSCRIPT=$BASEDIR/
	export UNXWEB=$BASEDIR/../../
fi

/soft/php/bin/php5 -c $BASEDIR/php-console.ini $*
