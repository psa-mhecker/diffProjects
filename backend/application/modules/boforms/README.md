#psa-boforms


##Vider le cache du Backoffice

[adresse Backoffice]/library/Pelican/Cache/public/clean_all_cache.php

##Droits repertoire
les repertoires :  
-  "application/modules/boforms/var/log"  
-  "application/modules/boforms/public/support"  
-  "application/modules/boforms/conf/local"  

doivent posseder les droits écriture pour Apache.  


##Fichiers de configuration local
les fichiers présent dans "application/modules/boforms/conf/local/" ne doivent pas être écrasés

##Moteur de rendu
Pour la prévisualisation, BOFORMS fait appel au moteur de rendu réalisé par Fullsix. 
L'action est donc d'ajouter les instructions ProxyPass au ficher vhost
du Backoffice.
adapter les url cibles si besoin (celon l'environnement, la marque ...)

-  ProxyPass /version/vc http://re7.dpdcr.citroen.com/version/vc
-  ProxyPass /services/getflux http://re7.dpdcr.citroen.com/services/getflux
-  ProxyPass /connexion http://re7.dpdcr.citroen.com/connexion
-  ProxyPass /services/user-is-brand-id http://re7.dpdcr.citroen.com/services/user-is-brand-id
-  ProxyPass /images/social-media http://re7.dpdcr.citroen.com/images/social-media
-  ProxyPass /qas http://re7.dpdcr.citroen.com/qas


##Media / lien symbolique

Lien symbolique "boforms" à créer dans le dossier /public/media/modules 
exemple de commande : (path a adapter)  
ln -s /var/www/application/modules/boforms/public/ boforms