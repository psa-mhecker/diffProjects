# CHANGELOG 1.0.6


## Corrections de bugs
- [Formbuilder] Les mêmes groupes de site sont utilisés pour tous les sites pays, ils devraient être cloisonnés par site pays
- [Formbuilder] L'ordre des composants dans l'interface formbuilder ne correspondent pas toujours à l'ordre décrit dans le flux XML de l'instance.
- [Formbuilder][previsualisation] La prévisualisation pour la version draft renvoi une page blanche
- [Formbuilder] Dans l'interface formbuilder, il arrive que le libellé généré pour le bouton d'un composants <html> soit "..."

## Nouvelles fonctionnalités
- [Module de configuration] Paramétrage du dossier contenant les logs (nouveau champ "Chemin des fichiers de logs" dans Configuration Générale)
- [Global] Ajout d'un script permettant de tester les appels vers le webservices : affichage de la requête et réponse du webservice + header. (ex : /_/module/boforms/BoForms_Administration_TestServices?service=getInstances)

## Autres changements
- [Formbuilder][prévisualisation] Mise à jour des appels JS et CSS pour prise en compte de la marque AP.
- [Formbuilder] Prise en charge de la traduction du composant "LEGAL_MENTION_ANSWER"
- [Formbuilder] Prise en charge de la structure des noeud "page" et "title" des flux xml AP.
- [Export des données] Les types de formulaires LANDING_PAGE, LANDING_PAGE_1, LANDING_PAGE_2 ne sont plus visible dans la sélection du filtre "Type(s) de formulaires" du module d'export.

## Changements de configuration

Création de la table 'psa_boforms_groupe_formulaire'
Suppression de la colonne 'GROUPE_ID' de la table 'psa_boforms_formulaire_site'
Ajout de la colonne 'SITE_ID' de la table 'psa_boforms_groupe'
(voir boforms/doc/1.0.6/sql/request-1.0.6.sql ou boforms/doc/1.0.6/sql/with-stored-procedure-request-1.0.6.sql pour la version avec procédures stockées)

Cette refonte des groupes de site necessite de recréer manuellement en Backoffice les groupes dans la partie "Gestion des groupes de site".

Le chemin vers le répertoire de log est maintenant personnalisable dans le module de configuration (Site d'administration > BOFORMS configuration > Configuration Générale > Chemin des fichiers de logs), vous pouvez y indiquer le chemin absolue du dossier contenant les logs. Par défaut, si le champ n'est pas rempli, le répertoire "application/modules/boforms/var/log/" est utilisé.
