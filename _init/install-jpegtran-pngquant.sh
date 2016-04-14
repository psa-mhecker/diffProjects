#!/usr/bin/env bash
LOG_FILE=/tmp/install.log
TMP_DIR="tmp-psa"
cd /tmp
if [ -d "$TMP_DIR" ]; then
rm -fr /tmp/$TMP_DIR
fi
mkdir $TMP_DIR
cd $TMP_DIR
echo "removing old binary "
apt-get purge pngquant > $LOG_FILE 2>&1
apt-get purge libjpeg-progs >> $LOG_FILE 2>&1

echo "Installing libraries"
apt-get install libpng-dev >> $LOG_FILE 2>&1
apt-get install libjpeg62-dev >> $LOG_FILE 2>&1
echo "installing jpegtran"
wget http://www.ijg.org/files/jpegsrc.v9a.tar.gz >> $LOG_FILE 2>&1
tar -xzvf jpegsrc.v9a.tar.gz >> $LOG_FILE 2>&1
cd jpeg-9a
./configure >> $LOG_FILE 2>&1
make install >> $LOG_FILE 2>&1
echo "Installing pngquant "
cd ..
git clone https://github.com/pornel/pngquant.git >> $LOG_FILE 2>&1
cd pngquant
make install >> $LOG_FILE 2>&1
ALL_OK=1
hash jpegtran 2>/dev/null || { ALL_OK=0; }
hash pngquant 2>/dev/null || { ALL_OK=0; }
if [ $ALL_OK -eq 0 ] 2>/dev/null
then
    echo "Une erreur est survenue voici le fichier de log"
    less $LOG_FILE
else
	echo "cleaning"
	cd /tmp
	rm -fr /tmp/$TMP_DIR
	rm $LOG_FILE
	echo "finished"

fi
