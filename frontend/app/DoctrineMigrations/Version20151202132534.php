<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151202132534 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // ajout du block Test CONFISHOW
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (99, 1, 'NDP_CS99_CONFISHOW', 0, NULL, 'Cms_Page_Ndp_Cs99Confishow', 'Cs99ConfigshowTestStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')");
        // ajout de la trance la derniere position de la zonne dynamique
        $this->addSql("INSERT INTO `psa_zone_template` VALUES (999,'NDP_CS99_CONFISHOW',1015,150,99,99,99, NULL,NULL,-2)");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_ID=99 ");
        $this->addSql("DELETE FROM `psa_zone` WHERE ZONE_ID=99 ");
    }
}
