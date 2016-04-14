# Worflow GIT

## Forker le projet 

Aller sur [github](https://github.com)

1.) s'identifier

2.) Aller jusqu'a la page de l'organisation ITK.

3.) cliquer sur le projet itk/psa-ndp.

4.) En haut à droite cliquer sur le bouton Fork.

5.) Choisir son avatar dans la popup qui s'ouvre. Laisser la moulinette se faire jusqu'a l'apparition de la page qui indique votre-nom-d-utilisateur/psa-ndp

## Récupérer son fork en local

 1.) sur la droite de la page vous devez voir un champs texte qui a pour titre "**HTTPS clone URL**" selectionner son contenu et faire **ctrl+C** pour le copier

 2.) ouvrir une invite de ligne de commande et taper :

```
cd workspace
git clone https://github.com/psa-acariou/psa-ndp.git
```

 3.) cela va créer un répertoire **psa-ndp** dans répertoire de travail, se placer dans ce repertoire

```
cd psa-ndp
```

4.) verifier que vous vous trouvez bien sur la branche develop

```
git checkout develop
```

5.) pour visualiser les dépots distants configuré pour votre fork tapez la commande

```
git remote -v
```

vous devez voir

```
origin	https://github.com/psa-acariou/psa-ndp.git (fetch)
origin	https://github.com/psa-acariou/psa-ndp.git (push)
```

Il faut maintenant configurer le dépot distant de votre fork, c'est à dire il faut mentionner à GIT quel est le dépot qui va permettre de mettre votre fork à jour

```
git remote add upstream https://github.com/itkg/psa-ndp.git
```

Suivit de

```
git remote -v
```

vous devez voir

```
origin	https://github.com/psa-acariou/psa-ndp.git (fetch)
origin	https://github.com/psa-acariou/psa-ndp.git (push)
upstream	https://github.com/itkg/psa-ndp.git (fetch)
upstream	https://github.com/itkg/psa-ndp.git (push)
```

## Régler les problèmes de droits

Créer un fichier nommé `.gitconfig` avec le contenu suivant :

```
[user]
	email = prenom.nom@ext.mpsa.com
	name = psa-user
[core]
	autocrlf = input
```

Ce fichier est référencé dans le .gitignore vous n'avez donc pas à commiter.

## Convention de nommage

Les tranches des développeurs Front doivent être préfixé par "front-" ex "front-contenu-3-colonnes"

## test du bon fonctionnement 

### Créer une branche

Nous ne travaillerons que dans le répertoire **assets** qui se trouve à la racine du projet.

```
git checkout -b utilisateur-branche-de-test
```

vous devez vous trouver sur votre branche, créer un nouveau répertoire dans assets et  vous placer dedans
```
cd assets
mkdir nom-d-utilisateur
cd nom-d-utilisateur
```

créer un fichier readme.md

```
touch readme.md
```

Editer le fichier et mettre un texte dedans puis commiter

```
git add readme.md
git commit -m "Je test un commit sur mon fork"
```

Mettre à jour son fork en récupérant l'état de l'index du projet principal pour éviter les conflits

```
git fetch upstream
```
On se remet sur la branche principal

```
git checkout develop
```

On merge la branche du projet principal sur la branche du même nom du projet Fork

```
git merge upstream/develop
```

On retourne sur notre branche de travail

```
git checkout test-psa-acariou
```

Retour console

```
Basculement sur la branche 'test-psa-acariou'
```

On met à jour l'index de notre branche avec celui de la branche principal  

```
git rebase develop
```

Retour console

```
La branche courante test-psa-acariou est à jour.
```

Une fois notre travail terminé on pousse notre travail sur notre fork distant et sur le projet principal.

```
git push -u origin test-psa-acariou
```

Il ne reste plus qu'a aller sur github sur le projet principal du projet et valider la demande de Pull Request.

## Mettre à jour les dépendances

Il faut veiller à mettre à jour régulièrement les dépendances du projet global, pour ce faire lancez cette commande depuis la racine du projet:

```
composer install
```

**composer doit bien entendu être installé sur la machine**

## Mettre à jour la BDD

depuis un terminal, connectez vous à la VM *(user/pwd: root/root)*

```
ssh root@psa-ndp.lxc
```

une fois connecté changer de répertoire:

```
cd /var/www/frontend
``` 

executez le script de migration:

```
php app/console d:m:m
```

le script vous demandera une confirmation tapez **y** pour valider, le script de migration mettra votre BDD à jour.
