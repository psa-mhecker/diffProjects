## Evolutions
- BOFORMS-710 [BOFORMS] (Tri-marque) Traduction des référentiels - évolution temporaire
- BOFORMS-639 [BO FORMS] Copie simple - Duplication simplifiée
- BOFORMS-333 [BOFORMS] (recette BE) Traduction des composants avancés - ajout d'un copier/coller pour dupliquer les champs entre web et mobile
- BOFORMS-335 [BOFORMS] Journal d'activité - élargir la période 
- optimisation de l'export excel passage à une version plus récente de la librairie (réalisé avant le développement des évolutions).
- BOFORMS-656 Traduction des TAG EVENT
- BOFORMS-794 [BOFORMS] (TRIMARQUE) Prise en compte du message d'erreur global pour tous les formulaires 
- BOFORMS-160 [BOFORMS] Création nouveau formulaire : changement du bouton radio par la checkbox / champ "indiquer la cible : particulier ou professionnel" ET "indiquer le device : web ou mobile". 
- BOFORMS-778 [BOFORMS] Traçabilité des actions de modification dans le BO


## Changements mineurs
- BOFORMS-750 P2 [FORMS/BOFORMS] (AC/DS) (RECETTE/PREPROD) LPV2 - Texte bulle d'aide non reconnu dans le BOFORMS 

## Nouvelles fonctionnalités avec réserve
- BOFORMS-701 [BOFORMS] (Tri-marque) Permettre la traduction et l'ordonnancement des référentiels 
Seule la partie saisie des données de référence a été implémentée. Car cette jira est incompatible avec la jira 710

- BOFORMS-800 Prise en compt la configuration GTM lors de la publication
Une réserve sur la gestion des tag gtm qui se trouvent au niveau des lignes dans le flux xml (problème pour les identifier).
Une réserve sur la gestion des tag gtm qui se trouvent au niveau de la page car pas d'exemple de tag avec label au niveau d'une page (vérifier si ce cas peut arriver).

##Nouvelles Constantes de traduction
BOFORMS_BTN_COPY_PASTE_TRANSLATIONS_FROM_HTML BOFORMS_BTN_COPY_PASTE_TRANSLATIONS_FROM_MOBILE BOFORMS_MSG_OVERRIDE_TRANSLATION_FROM_HTML BOFORMS_MSG_OVERRIDE_TRANSLATION_FROM_MOBILE BOFORMS_MSG_OVERRIDE_TRANSLATION_INFO 
BOFORMS_TRAD_REF_KEY BOFORMS_TRADUCTIONREFERENTIELS BOFORMS_TRADUCTIONREFERENTIELS_LIST BOFORMS_TRADUCTIONREFERENTIELS_FOR_THIS_SITE
BOFORMS_TAG_GTM_LABEL
BOFORMS_DUPL_INSTANCE_OK_LACK_HIDDEN_DATA BOFORMS_DUPL_INSTANCE_OK_LACK_LABEL_DATA BOFORMS_DUPL_INSTANCE_OK_LACK_REFERENTIAL_DATA BOFORMS_DUPL_INSTANCE_OK_LACK_REF_LABEL_DATA 
BOFORMS_GLOBAL_PAGE_ERROR_MESSAGE 
BOFORMS_STEP_TAGS_UNDER_QUESTION


##Nouvelles tables en base pour traduire les référentiels
psa_boforms_traductions_referentiel psa_boforms_traductions_referentiel_datas

##Nouveau template back-office
le template pour le menu "traduction des référentiels" est créé par une requête sql jouée à l'install de la 1.2.6.
Après l'install, il faudra jouer la procédure "BO_FORMS_AJOUT_MENU.doc" pour faire apparaître le menu "Traduction des référentiels" en back-office.

## Corrections de bugs

