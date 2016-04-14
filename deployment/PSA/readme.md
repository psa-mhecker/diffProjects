Ce répetoire contient les scripts nécessaires au déploiement de NDP chez PSA
- intégration continue sur environnement INT
- intégration manuelle sur environnement REC

PSA_update_int.sh
Script de mise à jour de l'application. Enchaine les commandes "git pull", "composer", "grunt", "doctrine"

decache_bo.sh
decache_fo.sh
Scripts a mettre à la racine du projet. Ils permettent d'effacer les caches proprement, et de les regénérer


