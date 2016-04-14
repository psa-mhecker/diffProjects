<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160121114939 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                      ('6120', 'NDP_PC77_DIMENSION_VEHICULE', '1013', '150', '758', '14', '14', NULL, NULL, '-2'),
					  ('6121', 'NDP_PC23_MUR_MEDIA', '1013', '150', '802', '15', '15', NULL, NULL, '-2')");
        $this->addSql("UPDATE `psa_zone_template` SET ZONE_TEMPLATE_ORDER=18, ZONE_TEMPLATE_MOBILE_ORDER=18 where `ZONE_TEMPLATE_ID` = 5046");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_zone_template where ZONE_TEMPLATE_ID in (6120,6121)");
    }
}
