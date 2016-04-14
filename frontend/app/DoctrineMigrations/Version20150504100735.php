<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150504100735 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql("DELETE FROM psa_page_zone_multi WHERE ZONE_TEMPLATE_ID in (SELECT pzt.ZONE_TEMPLATE_ID FROM `psa_zone_template` pzt WHERE ZONE_ID = 801);");
        $this->addSql("DELETE FROM psa_page_zone WHERE ZONE_TEMPLATE_ID in (SELECT pzt.ZONE_TEMPLATE_ID FROM `psa_zone_template` pzt WHERE ZONE_ID = 801);");
        $this->addSql("DELETE FROM psa_page_multi_zone_multi WHERE AREA_ID in (SELECT pzt.AREA_ID FROM `psa_zone_template` pzt WHERE ZONE_ID = 801);");
        $this->addSql("DELETE FROM psa_page_multi_zone WHERE AREA_ID in (SELECT pz.AREA_ID FROM `psa_zone_template` pz WHERE ZONE_ID = 801);");
        $this->addSql("DELETE FROM psa_zone_template  WHERE ZONE_ID = 801;");
        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES"
            . " ('9', 'NDP_PT3_JE_VEUX', '150', '122', '801', '9', NULL, NULL, NULL, '30');");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
      $this->addSql("DELETE FROM psa_zone_template WHERE ZONE_TEMPLATE_ID = '9';");

    }
}
