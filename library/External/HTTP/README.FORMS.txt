Voici un résumé à communiquer à Interakting pour le bon fonctionnement des formulaires :

1)	Les fichiers :
/Etudes/web/proto/cpw/application/controllers/FormsController.php
/Etudes/web/proto/cpw/application/views/scripts/forms/getformulaire.phtml
/Etudes/web/proto/cpw/application/views/scripts/forms/index.phtml
/Etudes/web/proto/cpw/application/forms/Listeformulaire.php

sont a copier dans le projet d'application Zend.

2)	La configuration du serveur apache demande l’activation de deux modules :
Mod_proxy
Mod_proxy_http


3)	L’ajout dans le fichier Httpd.conf de directive proxy :
ProxyPass          /forms/                       http://re7.dpdcr.citroen.com/
ProxyPassReverse   /forms/                       http://re7.dpdcr.citroen.com/

ProxyPass          /index/getflux                http://re7.dpdcr.citroen.com/index/getflux
ProxyPassReverse   /index/getflux                http://re7.dpdcr.citroen.com/index/getflux

ProxyPass          /qas                          http://re7.dpdcr.citroen.com/qas
ProxyPassReverse   /qas                          http://re7.dpdcr.citroen.com/qas

ProxyPass          /css/skin/                    http://re7.dpdcr.citroen.com/css/skin/
ProxyPassReverse   /css/skin/                    http://re7.dpdcr.citroen.com/css/skin/

ProxyPass          /index/postdata               http://re7.dpdcr.citroen.com/index/postdata
ProxyPassReverse   /index/postdata               http://re7.dpdcr.citroen.com/index/postdata

L’url proxyfiée pourra être au choix http://re7.dpdcr.citroen.com/ (url de recette)  ou  
http://dp-dcr.citroen.preprod.inetpsa.com (url de preprod)  ou   http://forms.citroen.fr (url de production)  

