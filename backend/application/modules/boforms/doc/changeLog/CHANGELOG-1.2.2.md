## Remarque importante

La version 1.2.1 de BoForms n'a été livrée que sur NDP car pas les ressources disponibles pour livrer sur les autres environnements (fêtes de fin d'année).
Donc pour les autres environnements que NDP, on passe directement de la 1.2.0 à la 1.2.2.

Il n'y a pas de requêtes sql à jouer pour la version 1.2.2. 
Par contre dans les fichiers sql de la version 1.2.2 on a reprit les requêtes sql de la 1.2.1 (à jouer pour passer de la version 1.2.0 à 1.2.2).  

## Corrections de bugs (1.2.1)
Liste des sites: contrôle du label à l'enregistrement pour ne pas avoir de doublons.
Bug ie9 liste déroulante ne s'affiche pas dans l'écran "créer un nouveau formulaire".
Affichage de la configuration: gestion de l'attribut "visible".
Modification pour rendre le champs HTML_OBLIGATORY supprimable.
Désactivation de formulaires - cas d'un pays multilingue.
Modification du code source pour supprimer le listener associé à un champ hidden qui a été supprimé du formulaire générique.
Gestion des espaces avant le cdata dans le champ commentary

## Evolution (1.2.1)
Gestion par site des url BO LP.

## Corrections de bugs (1.2.2)
La fonction d'activation/désactivation des formulaires a été revue pour gérer un générique sans personnalisés (jira 698)
Dans la liste des formulaires, modification du flag qui indique si une ligne est éditable ou pas (jira 699)
 