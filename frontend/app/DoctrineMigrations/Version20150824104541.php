<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150824104541 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_FO_PATH` = 'Pn14NavigationConfishowStrategy' WHERE `ZONE_ID` = 822");

        //Correction du libellé
        $this->addSql("UPDATE `psa_label_langue_site` set `LABEL_TRANSLATE` = 'Affichage du titre de la page' where `LABEL_ID` = 'NDP_DISPLAY_PAGE_TITLE'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_FO_PATH` = NULL WHERE `ZONE_ID` = 822");
    }
}
