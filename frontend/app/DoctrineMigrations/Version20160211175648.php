<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160211175648 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //AGILE - DEMO - TOUS Sprint - Home page
        $this->addSql("UPDATE `psa_template_page_area` SET `TEMPLATE_PAGE_AREA_ORDER` = 4, `LIGNE`= 4 WHERE TEMPLATE_PAGE_ID=1530 AND AREA_ID=150");
        $this->addSql("UPDATE `psa_template_page_area` SET `TEMPLATE_PAGE_AREA_ORDER` = 5, `LIGNE`= 5 WHERE TEMPLATE_PAGE_ID=1530 AND AREA_ID=122");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`,`AREA_ID`,`TEMPLATE_PAGE_AREA_ORDER`,`LIGNE`,`COLONNE`,`LARGEUR`,`HAUTEUR`,`IS_DROPPABLE`) VALUES
                        (1530,148,3,3,1,4,1,0)");
        $this->addSql("INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`,`ZONE_TEMPLATE_LABEL`,`TEMPLATE_PAGE_ID`,`AREA_ID`,`ZONE_ID`,`ZONE_TEMPLATE_ORDER`,`ZONE_TEMPLATE_MOBILE_ORDER`,`ZONE_TEMPLATE_TABLET_ORDER`,`ZONE_TEMPLATE_TV_ORDER`,`ZONE_CACHE_TIME`) VALUES
                        (6069,'NDP_PC19_SLIDESHOW',1530,148,754,2,2,NULL,NULL,-2),
                        (6081,'NDP_PF23_RANGE_BAR',1530,148,828,3,3,NULL,NULL,-2)");

        //AGILE - NDP_TP_SHOWROOM
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 3 , `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID` = 6129");
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 150, `ZONE_TEMPLATE_ORDER`= 22 , `ZONE_TEMPLATE_MOBILE_ORDER`= 22 WHERE `ZONE_TEMPLATE_ID` = 6127");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 23 , `ZONE_TEMPLATE_MOBILE_ORDER`= 23 WHERE `ZONE_TEMPLATE_ID` = 6146");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //AGILE - DEMO - TOUS Sprint - Home page
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE TEMPLATE_PAGE_ID=1530 AND AREA_ID=148");
        $this->addSql("UPDATE `psa_template_page_area` SET `TEMPLATE_PAGE_AREA_ORDER` = 3, `LIGNE`= 3 WHERE TEMPLATE_PAGE_ID=1530 AND AREA_ID=150");
        $this->addSql("UPDATE `psa_template_page_area` SET `TEMPLATE_PAGE_AREA_ORDER` = 4, `LIGNE`= 4 WHERE TEMPLATE_PAGE_ID=1530 AND AREA_ID=122");
        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` IN (6069,6081)");

        //AGILE - NDP_TP_SHOWROOM
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 5 , `ZONE_TEMPLATE_MOBILE_ORDER`= 5 WHERE `ZONE_TEMPLATE_ID` = 6129");
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 121, `ZONE_TEMPLATE_ORDER`= 3 , `ZONE_TEMPLATE_MOBILE_ORDER`= 3 WHERE `ZONE_TEMPLATE_ID` = 6127");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 22 , `ZONE_TEMPLATE_MOBILE_ORDER`= 22 WHERE `ZONE_TEMPLATE_ID` = 6146");

    }
}
