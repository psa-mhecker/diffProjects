#!/bin/bash
if [ $# -eq 2 ]; then
git diff --name-only  $1 $2 -- ../frontend/app/DoctrineMigrations/ | {
  while read line; do
        filename=$(basename "$line" .php)
        version="${filename/\Version/}"
        /usr/bin/php ../frontend/app/console d:m:e $version --write-sql
  done
}
cat doctrine_migration_* > databases_`date +%Y%m%d%H%M`.sql
rm -rf doctrine_migration_*
else
 echo "Veuillez entrez deux tags ou commit à comparer"
fi
echo "Fin du script"
