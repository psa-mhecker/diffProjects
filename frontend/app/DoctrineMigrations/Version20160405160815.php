<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160405160815 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
$this->addSql('create temporary table tmpTable (id int);

        insert  tmpTable
        (id)
        SELECT DISTINCT t2.ID
        FROM psa_ws_gdg_model_silhouette_site t1
          INNER JOIN psa_ws_gdg_model_silhouette_site t2 ON t1.LCDV6=t2.LCDV6 AND t1.SITE_ID=t2.SITE_ID AND t1.LANGUE_ID=t2.LANGUE_ID AND t1.GROUPING_CODE=t2.GROUPING_CODE
        WHERE t2.ID>t1.ID;
delete
from    psa_ws_gdg_model_silhouette_site
where   ID in (select id from tmpTable);');
        $this->addSql('ALTER IGNORE TABLE psa_ws_gdg_model_silhouette_site
ADD UNIQUE INDEX model_code (LCDV6, SITE_ID, LANGUE_ID, GROUPING_CODE)');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP INDEX model_code ON psa_ws_gdg_model_silhouette_site');
    }
}
