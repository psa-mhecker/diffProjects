<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150918154155 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
          UPDATE `psa_label_langue_site` SET `LABEL_TRANSLATE` = 'Affichage des regroupements de silhouettes de:' WHERE `LABEL_ID` LIKE  'NDP_MSG_SHOW_SILHOUETTE' AND `LANGUE_ID` = 1 AND `SITE_ID` = 1;
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("
          UPDATE `psa_label_langue_site` SET `LABEL_TRANSLATE` = 'Affichage des regroupements de silhouettes de la' WHERE `LABEL_ID` LIKE  'NDP_MSG_SHOW_SILHOUETTE' AND `LANGUE_ID` = 1 AND `SITE_ID` = 1;
        ");
    }
}
