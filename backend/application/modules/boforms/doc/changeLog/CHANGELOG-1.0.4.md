# CHANGELOG 1.0.4


## Corrections de bugs
- [Formbuilder] pour les champs de type hidden, les nouvelles valeurs appliqués dans un générique ne se répercutent pas dans le personnalisé.
- [Formbuilder] dans l'onglet "version", la suppression de la version brouillon n'est pas systématiquement prise en compte.

## Nouvelles fonctionnalités

## Autres changements
- [Export des Leads] refonte du processus de génération des exports CSV et XLS, la génération des colonnes est maintenant dynamique par rapport au retour du Webservice getReporting.
- [Formbuilder] suppression du champs "Listener parametre message" pour les formulaires du type Inscription et désinscription newsletter.
- [général] ajout d'une gestion des erreurs dans l'appel du service getReferential(), et ajout du ficher de log /var/log/service.log
- [Traduction] ajout clé de traduction suivante : "BOFORMS_ERROR_FORM_UNAVAILABLE", "BOFORMS_FORMSITE_LABEL_CRMPDV", "BOFORMS_FORMSITE_LABEL_CUSCO", "BOFORMS_FORMSITE_LABEL_PMS"

## Changements de configuration

