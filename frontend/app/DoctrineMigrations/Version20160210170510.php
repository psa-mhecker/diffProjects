<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160210170510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (378, 10, 1, 1, 1, 4, 1, 0),
            (378, 121, 2, 2, 1, 4, 1, 0),
            (378, 122, 5, 5, 1, 4, 1, 0),
            (378, 148, 4, 4, 1, 4, 1, 0),
            (378, 150, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`) VALUES
            (6263, \'NDP_PC60_SUMMARY_AND_SHOWROOM_CTA\', 378, 148, 818, 38, 38, NULL, NULL, -2)
        ');

        $this->addSql('DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` = 4479');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (378, 10, 1, 1, 1, 4, 1, 0),
            (378, 121, 2, 2, 1, 4, 1, 0),
            (378, 122, 4, 4, 1, 4, 1, 0),
            (378, 150, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` = 6263');
        $this->addSql('INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`) VALUES
            (4479, \'NDP_PC60_SUMMARY_AND_SHOWROOM_CTA\', 378, 150, 818, 19, 19, NULL, NULL, -2)
        ');
    }
}
