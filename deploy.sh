#!/bin/sh

# Avant de lancer le script il faut faire un diff entre le dernier commit et la dernière livraison
# git diff 9d8c9740ec75c1fb24c6cdf8d5c6f437762961fa 84b80af61ee507739b8a83d77ff8156815c767d8 --name-only > livraison.src
# L'utilisation de tags permettra des commandes simplifiées : git diff V2.0 V1.0 --name-only > livraison.src

# Dans le cas d'un rollback : faire un git checkout V1.0 avant le git diff

#!/bin/sh

git diff last_delivery --name-only > livraison.src

currentDate=`date "+%Y-%m-%d---%H-%M-%S"`
mkdir -p "$currentDate"
for i in $(cat livraison.src)
do
dir=$(dirname "$i")
echo "$i"

cp --parents "$i" "$currentDate"
done

tar -zcvf "delivery_$currentDate.tar" $currentDate

rm -Rf $currentDate

rm livraison.src

git tag -d last_delivery

git push last_delivery

git tag last_delivery

git tag $currentDate

git push last_delivery

git push $currentDate
