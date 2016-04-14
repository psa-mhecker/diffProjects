<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160216162618 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //AGILE - NDP_TP_TECHNO
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 121, `ZONE_TEMPLATE_ORDER`= 3, `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID`= 6250");
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 150, `ZONE_TEMPLATE_ORDER`= 4, `ZONE_TEMPLATE_MOBILE_ORDER`= 4  WHERE `ZONE_TEMPLATE_ID`= 6249");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE `TEMPLATE_PAGE_ID` = 1539 AND `AREA_ID` = 148");
        $this->addSql("UPDATE `psa_template_page_area` SET `TEMPLATE_PAGE_AREA_ORDER` = 3, `LIGNE`= 3 WHERE `TEMPLATE_PAGE_ID` = 1539 AND `AREA_ID` = 150");
        $this->addSql("UPDATE `psa_template_page_area` SET `TEMPLATE_PAGE_AREA_ORDER` = 4, `LIGNE`= 4 WHERE `TEMPLATE_PAGE_ID` = 1539 AND `AREA_ID` = 122");

        //AGILE - NDP_TP_SHOWROOM
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 3, `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID`= 6126");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 2, `ZONE_TEMPLATE_MOBILE_ORDER`= 2  WHERE `ZONE_TEMPLATE_ID`= 6129");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //AGILE - NDP_TP_TECHNO
        $this->addSql("UPDATE `psa_template_page_area` SET `TEMPLATE_PAGE_AREA_ORDER` = 4, `LIGNE`= 4 WHERE `TEMPLATE_PAGE_ID` = 1539 AND `AREA_ID` = 150");
        $this->addSql("UPDATE `psa_template_page_area` SET `TEMPLATE_PAGE_AREA_ORDER` = 5, `LIGNE`= 5 WHERE `TEMPLATE_PAGE_ID` = 1539 AND `AREA_ID` = 122");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`,`AREA_ID`,`TEMPLATE_PAGE_AREA_ORDER`,`LIGNE`,`COLONNE`,`LARGEUR`,`HAUTEUR`,`IS_DROPPABLE`) VALUES
                        (1539,148,3,3,1,4,1,0)");
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 148, `ZONE_TEMPLATE_ORDER`= 4,  `ZONE_TEMPLATE_MOBILE_ORDER`= 4  WHERE `ZONE_TEMPLATE_ID`= 6250");
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 148, `ZONE_TEMPLATE_ORDER`= 3, `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID`= 6249");

        //AGILE - NDP_TP_SHOWROOM
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 2, `ZONE_TEMPLATE_MOBILE_ORDER`= 2  WHERE `ZONE_TEMPLATE_ID`= 6126");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 3, `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID`= 6129");




    }
}
