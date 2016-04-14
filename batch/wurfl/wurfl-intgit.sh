#!/bin/sh
# Correspond au batch DLEI C???
# Batch de mise � jour des fichiers WURFL

# On se place dans le rep d'execution de ce script
# Cela evite les dysfonctionnement de cron
cd $(dirname $(readlink -f $0))

echo D�clenchement du batch de mise � jour WURFL

# Changer le fichier suivant pour utiliser un autre environnement
source ../env-intgit.sh

if [[ "$TYPE_ENVIRONNEMENT" == "" ]]; then
	echo "ERREUR : pas d'environnement indiqu�"
	exit 1
fi

echo =================================
date
echo R�pertoire de sortie : $(readlink -f $BACKEND_VAR_PATH)
echo Environnement : $SYMFONY_ENV / $TYPE_ENVIRONNEMENT

op chmod -R 777 $FRONTEND_VAR_PATH/cache
nice -n 10 ../../php.sh -d short_open_tag=on -d memory_limit=2000M wurfl.php $*
batch_return_code=$?
if [ $batch_return_code -ne 0 ]
then
	echo C??? : Script PHP WURFL a retourn� un code erreur $batch_return_code
	exit $batch_return_code
fi

op chmod -R 777 $FRONTEND_VAR_PATH/cache

date




