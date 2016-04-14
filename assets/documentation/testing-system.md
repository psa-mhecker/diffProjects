## Installation Karma avec Jasmine

Conditions :
"Remarque : Karma fonctionne actuellement sur Node.js 0,10, 0.12.x et 4.x. voir FAQ pour plus d'informations."


### Commande d'installation sur votre machine :

```
npm install -g karma-cli
```

### Configuration de Karma

Le fichier de configuration est karma.conf.js et il est situé dans /assets

Navigateur actuellement configuré :
- Chrome
- Firefox

Besoin de connaître l'architecture actuelle PSA informatique avant d'être en mesure de déployer le navigateur suivant :
- Safari
- IE

Watch de Karma est actuellement désactivé

### Commande pour lancer les tests :

```
karma start
karma start karma.conf.js --log-level debug --single-run
```

### Teamcity et Karma

Configuration Karma avec Teamcity :  http://karma-runner.github.io/0.13/plus/teamcity.html