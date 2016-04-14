<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150707102127 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
                        INSERT INTO `psa_zone_template`
                        (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`)
                        VALUES
                        (2100, 'NDP_PC7_DEUX_COLONNES', 290, 150, 781, 3, NULL, NULL, NULL, 30),
                        (2101, 'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS', 290, 150, 766, 8, NULL, NULL, NULL, 30),
                        (2102, 'NDP_PF6_DRAG_DROP', 290, 150, 786, 7, NULL, NULL, NULL, 30);
                        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` in (2100, 2101, 2102);");
    }
}
