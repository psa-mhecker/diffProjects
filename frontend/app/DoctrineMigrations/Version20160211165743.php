<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160211165743 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (1015, 10, 1, 1, 1, 4, 1, 0),
            (1015, 151, 2, 2, 1, 4, 1, 0),
            (1015, 122, 5, 5, 1, 4, 1, 0),
            (1015, 148, 4, 4, 1, 4, 1, 0),
            (1015, 150, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (1533, 10, 1, 1, 1, 4, 1, 0),
            (1533, 121, 2, 2, 1, 4, 1, 0),
            (1533, 122, 5, 5, 1, 4, 1, 0),
            (1533, 148, 4, 4, 1, 4, 1, 0),
            (1533, 150, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`) VALUES
            (6264, \'NDP_PC60_SUMMARY_AND_SHOWROOM_CTA\', 1015, 148, 818, 25, 25, NULL, NULL, -2)
        ');

        $this->addSql('INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`) VALUES
            (6265, \'NDP_PC60_SUMMARY_AND_SHOWROOM_CTA\', 1533, 148, 818, 22, 22, NULL, NULL, -2)
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (1015, 10, 1, 1, 1, 4, 1, 0),
            (1015, 151, 2, 2, 1, 4, 1, 0),
            (1015, 122, 4, 4, 1, 4, 1, 0),
            (1015, 150, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (1533, 10, 1, 1, 1, 4, 1, 0),
            (1533, 121, 2, 2, 1, 4, 1, 0),
            (1533, 122, 4, 4, 1, 4, 1, 0),
            (1533, 150, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` = 6264');
        $this->addSql('DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` = 6265');
    }
}
