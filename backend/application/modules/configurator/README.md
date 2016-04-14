# psa-config

## INTEGRATION DES MEDIA
- Ajouter un lien symbolique dans public/media/modules/
=> ln -s ../../../application/modules/configurator/public configurator

- Modifier le vhost du servername media et ajouter backend
Alias /modules/ "/var/www/html/backend/public/media/modules/"
