Ce r�petoire contient les scripts n�cessaires au d�ploiement de NDP chez PSA
- int�gration continue sur environnement INT
- int�gration manuelle sur environnement REC

PSA_update_int.sh
Script de mise � jour de l'application. Enchaine les commandes "git pull", "composer", "grunt", "doctrine"

decache_bo.sh
decache_fo.sh
Scripts a mettre � la racine du projet. Ils permettent d'effacer les caches proprement, et de les reg�n�rer


