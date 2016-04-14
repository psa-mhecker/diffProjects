cd /var/www/_init/dump
sudo mysqldump -u psa-ndp -ppsa-ndp psa-ndp > psa-ndp-tables.sql
sudo mysql -u psa-ndp -ppsa-ndp INFORMATION_SCHEMA --skip-column-names --batch -e "select table_name from tables where table_type = 'VIEW' and table_schema = 'psa-ndp'" | xargs mysqldump -u psa-ndp -ppsa-ndp psa-ndp > psa-ndp-views.sql
sudo chown -R www-data:www-data /var/www/_init/dump
