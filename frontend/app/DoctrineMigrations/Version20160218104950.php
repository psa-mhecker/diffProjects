<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160218104950 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //AGILE - DEMO - TOUS Sprint - Home page
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 17,`ZONE_TEMPLATE_MOBILE_ORDER` = 17 WHERE `ZONE_TEMPLATE_ID`= 6077");

        $this->addSql("INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`,`ZONE_TEMPLATE_LABEL`,`TEMPLATE_PAGE_ID`,`AREA_ID`,`ZONE_ID`,`ZONE_TEMPLATE_ORDER`,`ZONE_TEMPLATE_MOBILE_ORDER`,`ZONE_TEMPLATE_TABLET_ORDER`,`ZONE_TEMPLATE_TV_ORDER`,`ZONE_CACHE_TIME`) VALUES
                        (6268,'NDP_PF11_RECHERCHE_POINT_DE_VENTE',1530,150,812,14,14,NULL,NULL,-2),
                        (6269,'NDP_PF27_Car_Picker',1530,150,782,15,15,NULL,NULL,-2)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //AGILE - DEMO - TOUS Sprint - Home page
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 14,`ZONE_TEMPLATE_MOBILE_ORDER` = 14 WHERE `ZONE_TEMPLATE_ID`= 6077");

        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` IN (6268,6269)");

    }
}
