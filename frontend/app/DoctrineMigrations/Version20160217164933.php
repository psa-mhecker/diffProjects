<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160217164933 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO `psa_area` (`AREA_ID`, `AREA_LABEL`, `AREA_PATH`, `AREA_HEAD`, `AREA_FOOT`, `AREA_ID_DIV`, `AREA_UPDATE_FRONT`, `AREA_HORIZONTAL`, `AREA_DROPPABLE`, `AREA_MOBILE`, `AREA_HEAD_MOBILE`, `AREA_FOOT_MOBILE`) VALUES
            (152, "Peugeot-Showroom recapitulatif cta", NULL, "", "</div>", NULL, NULL, NULL, NULL, 0, "", ""),
            (153, "Peugeot-Showroom zone dynamique", NULL, \'<div class="row" >
                <div class="small-12 large-12 columns" id="leftInfoContainer">\', "</div>", NULL, NULL, NULL, 1, 0, "", "")
        ');

        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');
        $tables = array('psa_page_multi_zone', 'psa_page_multi_zone_content', 'psa_page_multi_zone_cta', 'psa_page_multi_zone_cta_cta', 'psa_page_multi_zone_media', 'psa_page_multi_zone_multi', 'psa_page_multi_zone_multi_cta', 'psa_page_multi_zone_multi_cta_cta');

        foreach ($tables as $table) {
            $this->addSql('UPDATE '.$table.' JOIN `psa_page_version` ON '.$table.'.PAGE_ID = psa_page_version.PAGE_ID AND '.$table.'.PAGE_VERSION = psa_page_version.PAGE_VERSION SET '.$table.'.AREA_ID = 153 WHERE '.$table.'.AREA_ID = 150 AND psa_page_version.TEMPLATE_PAGE_ID IN (378, 1015, 1533)');
        }

        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');

        $this->addSql('DELETE FROM `psa_template_page_area` WHERE AREA_ID = 150 AND TEMPLATE_PAGE_ID IN (1015, 1533, 378)');
        $this->addSql('DELETE FROM `psa_template_page_area` WHERE AREA_ID = 148 AND TEMPLATE_PAGE_ID IN (1015, 1533, 378)');

        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (1015, 10, 1, 1, 1, 4, 1, 0),
            (1015, 151, 2, 2, 1, 4, 1, 0),
            (1015, 122, 5, 5, 1, 4, 1, 0),
            (1015, 152, 4, 4, 1, 4, 1, 0),
            (1015, 153, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (1533, 10, 1, 1, 1, 4, 1, 0),
            (1533, 121, 2, 2, 1, 4, 1, 0),
            (1533, 122, 5, 5, 1, 4, 1, 0),
            (1533, 152, 4, 4, 1, 4, 1, 0),
            (1533, 153, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (378, 10, 1, 1, 1, 4, 1, 0),
            (378, 121, 2, 2, 1, 4, 1, 0),
            (378, 122, 5, 5, 1, 4, 1, 0),
            (378, 152, 4, 4, 1, 4, 1, 0),
            (378, 153, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('UPDATE `psa_zone_template` SET AREA_ID = 152 WHERE ZONE_TEMPLATE_ID IN (6263, 6264, 6265)');
        $this->addSql('UPDATE `psa_zone_template` SET AREA_ID = 153 WHERE ZONE_TEMPLATE_ID IN (
            4466,
            4467,
            4468,
            4469,
            4470,
            4472,
            4473,
            4475,
            4476,
            4477,
            4478,
            4480,
            4481,
            4482,
            4483,
            4484,
            4485,
            4486,
            4487,
            4488,
            4489,
            4490,
            4491,
            4492,
            4493,
            5000,
            6264,
            6127,
            6130,
            6131,
            6132,
            6133,
            6135,
            6136,
            6137,
            6138,
            6139,
            6140,
            6141,
            6142,
            6143,
            6144,
            6145,
            999,
            5017,
            5018,
            5019,
            5036,
            5037,
            5038,
            5039,
            6064,
            6065,
            6066,
            6067,
            6078,
            6082,
            6084,
            6085,
            6090)');

        $this->addSql('DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID = 6134');

        $this->addSql('DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID = 6083');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');
        $tables = array('psa_page_multi_zone', 'psa_page_multi_zone_content', 'psa_page_multi_zone_cta', 'psa_page_multi_zone_cta_cta', 'psa_page_multi_zone_media', 'psa_page_multi_zone_multi', 'psa_page_multi_zone_multi_cta', 'psa_page_multi_zone_multi_cta_cta');

        foreach ($tables as $table) {
            $this->addSql('UPDATE '.$table.' JOIN `psa_page_version` ON '.$table.'.PAGE_ID = psa_page_version.PAGE_ID AND '.$table.'.PAGE_VERSION = psa_page_version.PAGE_VERSION SET '.$table.'.AREA_ID = 150 WHERE '.$table.'.AREA_ID = 153 AND psa_page_version.TEMPLATE_PAGE_ID IN (378, 1015, 1533)');
        }

        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');

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

        $this->addSql('REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES
            (378, 10, 1, 1, 1, 4, 1, 0),
            (378, 121, 2, 2, 1, 4, 1, 0),
            (378, 122, 5, 5, 1, 4, 1, 0),
            (378, 148, 4, 4, 1, 4, 1, 0),
            (378, 150, 3, 3, 1, 4, 1, 0)
        ');

        $this->addSql('UPDATE `psa_zone_template` SET AREA_ID = 148 WHERE ZONE_TEMPLATE_ID IN (6263, 6264, 6265)');
        $this->addSql('UPDATE `psa_zone_template` SET AREA_ID = 150 WHERE ZONE_TEMPLATE_ID IN (
            4466,
            4467,
            4468,
            4469,
            4470,
            4472,
            4473,
            4475,
            4476,
            4477,
            4478,
            4480,
            4481,
            4482,
            4483,
            4484,
            4485,
            4486,
            4487,
            4488,
            4489,
            4490,
            4491,
            4492,
            4493,
            5000,
            6264,
            6127,
            6130,
            6131,
            6132,
            6133,
            6135,
            6136,
            6137,
            6138,
            6139,
            6140,
            6141,
            6142,
            6143,
            6144,
            6145,
            999,
            5017,
            5018,
            5019,
            5036,
            5037,
            5038,
            5039,
            6064,
            6065,
            6066,
            6067,
            6078,
            6082,
            6084,
            6085,
            6090)');
        $this->addSql('DELETE FROM `psa_template_page_area` WHERE AREA_ID = 153');
        $this->addSql('DELETE FROM `psa_template_page_area` WHERE AREA_ID = 152');
        $this->addSql('DELETE FROM `psa_area` WHERE AREA_ID = 152');
        $this->addSql('DELETE FROM `psa_area` WHERE AREA_ID = 153');
    }
}
