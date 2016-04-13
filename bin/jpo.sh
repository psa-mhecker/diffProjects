#!/bin/sh
# JPEG PNG OPTIMIZER
# Ce script intercepte les appels a GraphicsMagick (programme "gm") 
# pour les enchainer avec l'outils d'optimisation "pngquantitizer" et je jpeg "jpegtran"
# Parametres en entrée : les memes que lors d'un appel à "gm".

# Exemple d'appel : jpo.sh gm convert INPUT.JPG output.png

### CONFIG. Attention, les chemins doivent être en absolue et pas en relatif
# Pour désactiver le log : remplacer le chemin par /dev/null
#LOGFILE=$(dirname $(readlink -f $0))/../var/logs/jpo.log
#LOGFILE=./jpo.log
LOGFILE=/dev/null
#LOGFILE=/dev/null
# Indiquez ici le chemin complet du répertoire "bin" de graphic magick (où se trouve l'exe "gm")
GMPATH=/soft/apc22/modules/GraphicsMagick/bin/
# Indiquez ici le chemin complet de l'exe "pngquant"
#PNGQUANT=/soft/apc22/modules/pngquant/bin/pngquant
PNGQUANT=/users/cpw00/modules/pngquant/bin/pngquant
# Indiquez ici le chemin complet de l'exe "jpegtran"
#JPGTRAN=/users/usersdev/cpw/bin/jpegtran.exe/bin/jpegtran
JPGTRAN=/users/cpw00/modules/jpeg-9a/bin/jpegtran


# Récupération des 2 derniers parametres de la ligne de command gm, qui sont les fichiers d'entrées et de sorties
input=${*: -2:1}
output=${*: -1:1}
# Fichier temporaire pour traitement pngquant
TMP=$(mktemp)

echo jpo : $(date) INPUT=$input OUTPUT=$output >> $LOGFILE
echo $* >> $LOGFILE

if [ ! -f "$input" ]; then
	echo jpo : le fichier $input n\'est pas accessible >> $LOGFILE
	exit 1
fi

# Execution de la ligne de commande passée en parametre du script 
$GMPATH/$* >> $LOGFILE 2>&1
last_err=$?

# Si GM n'a pas fonctionné, on s'arrete là
if [ "$last_err" != "0" ]; then
	echo jpo : l\'appel à GM a echoué avec le code d\'erreur : $last_err >> $LOGFILE
	exit $last_err  
fi

# Optimisation de l'image générée par GM

## PNG
echo jpo : optimisation PNG de $output vers fichier temporaire  $TMP >> $LOGFILE
$PNGQUANT -f --skip-if-larger --nofs --speed 1  "$output" -o "$TMP" >> $LOGFILE 2>&1
last_err=$?
# Voir les codes retour ici : https://github.com/pornel/pngquant/blob/master/rwpng.h#L45
# Codes habituels : 0=OK ; 25=pas un jpg ; 98=fichier ne se compresse pas assez ; 
echo jpo : code retour png = $last_err >> $LOGFILE
if [ "$last_err" == "0" ]; then	
	echo "jpo : orig:$input ; gm:$output ; pngquant:$TMP" >> $LOGFILE
	echo "jpo : fichier PNG $input : avant = $(filesize $output) - après = $(filesize $TMP)"  >> $LOGFILE
	cp $TMP $output >> $LOGFILE 2>&1
	rm $TMP >> $LOGFILE 2>&1
else
## JPG
	echo jpo : optimisation JPG de $output vers fichier temporaire  $TMP >> $LOGFILE
	$JPGTRAN -copy none -optimize -progressive -outfile "$TMP" "$output"  >> $LOGFILE 2>&1
	last_err=$?
	# Codes habituels : 0=OK ; 1=KO
	echo jpo : code retour = $last_err >> $LOGFILE
	if [ "$last_err" == "0" ]; then	
		echo "jpo : orig:$input ; gm:$output ; jpegtran:$TMP" >> $LOGFILE
		echo "jpo : fichier JPG $input : avant = $(filesize $output) - après = $(filesize $TMP)"  >> $LOGFILE
		cp $TMP $output >> $LOGFILE 2>&1
		rm $TMP >> $LOGFILE 2>&1
	fi
fi

echo jpo : fin traitement à $(date) >> $LOGFILE

