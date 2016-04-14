<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160218164701 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //AGILE - NDP_TP_SHOWROOM   
        $this->addSql("UPDATE `psa_template_page_area` SET `AREA_ID` = 151 WHERE `TEMPLATE_PAGE_ID` = 1533 AND `AREA_ID` = 121");
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 151 WHERE `TEMPLATE_PAGE_ID` = 1533 AND `AREA_ID` = 121");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //AGILE - NDP_TP_SHOWROOM
        $this->addSql("UPDATE `psa_template_page_area` SET `AREA_ID` = 121 WHERE `TEMPLATE_PAGE_ID` = 1533 AND `AREA_ID` = 151");
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 121 WHERE `TEMPLATE_PAGE_ID` = 1533 AND `AREA_ID` = 151");

    }
}
