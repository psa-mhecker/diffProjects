<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160107094113 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
    (NULL, 1, \'NDP_PC52_APV\', 0, NULL, \'Cms_Page_Ndp_Pc52Apv\', \'Pc52ApvStrategy\', 0, 0, 0, NULL, NULL, 28, 0, \'\');');
        $this->addSql('INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
    (NULL, 1, \'NDP_PC53_APV\', 0, NULL, \'Cms_Page_Ndp_Pc53Apv\', \'Pc53ApvStrategy\', 0, 0, 0, NULL, NULL, 28, 0, \'\');');

        //MAJ Gabarit APV
        $this->addSql('REPLACE INTO `psa_template_page` (`TEMPLATE_PAGE_ID`, `SITE_ID`, `PAGE_TYPE_ID`, `TEMPLATE_PAGE_LABEL`, `TEMPLATE_PAGE_GENERAL`) VALUES
    (1531, 2, 105, \'AGILE - DEMO - Sprint4 - APV prestation (ex : G22)\', NULL),
    (1532, 2, 104, \'AGILE - DEMO - Sprint4 - APV (ex : G21)\', NULL);
      ');

        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
    (1531, 10, 1, 1, 1, 4, 1, 0),
    (1531, 121, 2, 2, 1, 4, 1, 0),
    (1531, 122, 5, 5, 1, 4, 1, 0),
    (1531, 148, 3, 3, 1, 4, 1, 0),
    (1531, 150, 4, 4, 1, 4, 1, 0),
    (1532, 10, 1, 1, 1, 4, 1, 0),
    (1532, 121, 2, 2, 1, 4, 1, 0),
    (1532, 122, 4, 4, 1, 4, 1, 0),
    (1532, 150, 3, 3, 1, 4, 1, 0);
      ');

        $this->addSql('REPLACE INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`) VALUES
(6086, \'NDP_PT21_NAVIGATION\', 1531, 10, 798, 1, 1, NULL, NULL, -2),
(6087, \'NDP_PN7_ENTETE\', 1531, 121, 791, 2, 2, NULL, NULL, -2),
(6088, \'PT2\', 1531, 122, 800, 7, 3, NULL, NULL, -2),
(6089, \'NDP_PC5_UNE_COLONNE\', 1531, 150, 776, 5, NULL, NULL, NULL, -2),
(6091, \'NDP_PT21_NAVIGATION\', 1532, 10, 798, 1, 1, NULL, NULL, -2),
(6092, \'NDP_PN7_ENTETE\', 1532, 121, 791, 2, 2, NULL, NULL, -2),
(6093, \'PT2\', 1532, 122, 800, 5, 4, NULL, NULL, -2),
(6094, \'NDP_PC5_UNE_COLONNE\', 1532, 150, 776, 3, 3, NULL, NULL, -2),
(6095, \'NDP_PC52_APV\', 1532, 150, 3301, 4, NULL, NULL, NULL, -2),
(6114, \'NDP_PC12_3_COLONNES\', 1531, 150, 760, 6, NULL, NULL, NULL, -2),
(6116, \'NDP_PC53_APV\', 1531, 148, 3302, 3, NULL, NULL, NULL, -2),
(6117, \'NDP_PC5_UNE_COLONNE\', 1531, 148, 776, 4, NULL, NULL, NULL, -2);
        ');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM `psa_zone` WHERE `ZONE_LABEL` =  \'NDP_PC52_APV\'');
        $this->addSql('DELETE FROM `psa_zone` WHERE `ZONE_LABEL` =  \'NDP_PC53_APV\'');
    }
}
