# CHANGELOG 1.1.0


## Corrections de bugs
- [Backoffice][Gestion des formulaires] une erreur SQL apparaît lorsque le webservice getInstances() retourne une valeur de type string dans le noeud <editable>
- [Backoffice][API JIRA] un problème d'encodage du mot de passe pour le proxy empêche la connexion à l'API Jira
- [Formbuiler][AB Testing] Lorsqu'un formulaire n'est pas valide xsd, la version ABtesting générée n'est pas réellement créée. Il manque un message d'erreur pour informer l'utilisateur

## Nouvelles fonctionnalités
- [Backoffice] Nouvelle fonctionnalité pour la duplication des instances
- [Formbuiler] prise en charge du noeud <commentary> et de son attribut "visible"

## Autres changements
- [Traduction I18N] modification de la liste des clés des composants avancés pour les marques AP et DS (cf AP-DS.request-1.1.0.sql)
- [Formbuilder][preview] remise à niveau du code d'intégration du moteur de rendu
- Readme.md mise à jour des instructions ProxyPass

##Nouvelles Constantes de traduction
BOFORMS_SAVE_FAILED_ABTESTING, BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT, BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT, BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK, BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY, BOFORMS_REFERENTIAL_FORM_TYPE_RLC, BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE, 
BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH, BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM, BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM, BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM, BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM, BOFORMS_VISIBLE, BOFORMS_LABEL_TARGET_FORM, BOFORMS_LABEL_CONFIRM_DUPLICATE, BOFORMS_LABEL_RESULT, BOFORMS_DUPLICATE_OTHER_FORM, BOFORMS_FORM_DUPLICATION_PARAMETERS

## Changements de configuration
- [virtualHost] harmonisation des ProxyPass ajout des instructions (adapter les URL cible si besoin) :
ProxyPass /version/vc http://re7.dpdcr.citroen.com/version/vc
ProxyPass /services/getflux http://re7.dpdcr.citroen.com/services/getflux
ProxyPass /connexion http://re7.dpdcr.citroen.com/connexion
ProxyPass /images/social-media http://re7.dpdcr.citroen.com/images/social-media
