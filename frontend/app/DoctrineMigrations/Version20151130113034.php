<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151130113034 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //Gabarits sprint 3
        $this->addSql("UPDATE `psa_template_page` set `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint3 - NavFull' where TEMPLATE_PAGE_ID=1013");
        $this->addSql("UPDATE `psa_template_page` set `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint3 - NavLight' where TEMPLATE_PAGE_ID=1015");
        $this->addSql("UPDATE `psa_template_page` set `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint3 - Dealer Locator' where TEMPLATE_PAGE_ID=1518");
        $this->addSql("INSERT INTO `psa_template_page` (`TEMPLATE_PAGE_ID`, `SITE_ID`, `PAGE_TYPE_ID`, `TEMPLATE_PAGE_LABEL`) VALUES
                        (1530,2,25,'AGILE - DEMO - TOUS Sprint - Home page')");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`,`AREA_ID`,`TEMPLATE_PAGE_AREA_ORDER`,`LIGNE`,`COLONNE`,`LARGEUR`,`HAUTEUR`,`IS_DROPPABLE`) VALUES
                        (1530,10,1,1,1,4,1,0),
                        (1530,121,2,2,1,4,1,0),
                        (1530,122,4,4,1,4,1,0),
                        (1530,150,3,3,1,4,1,0)");

        //NavFull
        $this->addSql("DELETE from `psa_zone_template` where ZONE_TEMPLATE_ID=5028");
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('6060', 'NDP_PC8_DEUX_COLONNES_TEXTE', '1013', '150', '751', '11', '11', NULL, NULL, '-2'),
                        ('6061', 'NDP_PC59_TOOLS', '1013', '150', '777', '13', '13', NULL, NULL, '-2'),
                        ('6062', 'NDP_PC79_LIGHT_MEDIA_WALL', '1013', '150', '816', '14', '14', NULL, NULL, '-2')");

        //NavLight
        $this->addSql("DELETE from `psa_zone_template` where ZONE_TEMPLATE_ID=5040");
        $this->addSql("UPDATE `psa_zone_template` set ZONE_TEMPLATE_ORDER=13 , ZONE_TEMPLATE_MOBILE_ORDER=13 WHERE ZONE_TEMPLATE_ID=5035");
        $this->addSql("UPDATE `psa_zone_template` set ZONE_TEMPLATE_MOBILE_ORDER=14 WHERE ZONE_TEMPLATE_ID=5045");
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('6063', 'NDP_PN15_CONFISHOW_HEADER', '1015', '151', '823', '5', '5', NULL, NULL, '-2'),
                        ('6064', 'NDP_PC8_DEUX_COLONNES_TEXTE', '1015', '150', '751', '15', '15', NULL, NULL, '-2'),
                        ('6065', 'NDP_PC59_TOOLS', '1015', '150', '777', '16', '16', NULL, NULL, '-2'),
                        ('6066', 'NDP_PC79_LIGHT_MEDIA_WALL', '1015', '150', '816', '16', '16', NULL, NULL, '-2'),
                        ('6067', 'NDP_PF42_SELECTEUR_DE_TEINTE_360', '1015', '150', '784', '17', '17', NULL, NULL, '-2')");

        //HP
        $this->addSql("INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`,`ZONE_TEMPLATE_LABEL`,`TEMPLATE_PAGE_ID`,`AREA_ID`,`ZONE_ID`,`ZONE_TEMPLATE_ORDER`,`ZONE_TEMPLATE_MOBILE_ORDER`,`ZONE_TEMPLATE_TABLET_ORDER`,`ZONE_TEMPLATE_TV_ORDER`,`ZONE_CACHE_TIME`) VALUES
                        (6068,'NDP_PT21_NAVIGATION',1530,10,798,1,1,NULL,NULL,-2),
                        (6069,'NDP_PC2_CONTENU_TEXTE_RICHE',1530,150,750,19,19,NULL,NULL,-2),
                        (6070,'NDP_PC5_UNE_COLONNE',1530,150,776,17,17,NULL,NULL,-2),
                        (6071,'NDP_PC7_DEUX_COLONNES',1530,150,781,22,22,NULL,NULL,-2),
                        (6072,'NDP_PC9_UN_ARTICLE_UN_VISUEL',1530,150,752,9,9,NULL,NULL,-2),
                        (6073,'NDP_PC12_3_COLONNES',1530,150,760,15,15,NULL,NULL,-2),
                        (6074,'NDP_PC40_CTA',1530,150,771,7,7,NULL,NULL,-2),
                        (6075,'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS',1530,150,766,21,21,NULL,NULL,-2),
                        (6076,'NDP_PC79_LIGHT_MEDIA_WALL',1530,150,816,17,17,NULL,NULL,-2),
                        (6077,'NDP_PT2_FOOTER',1530,122,800,31,31,NULL,NULL,-2)");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //Gabarits Sprint 2
        $this->addSql("UPDATE `psa_template_page` set `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint2 - NavFull' where `TEMPLATE_PAGE_ID`=1013");
        $this->addSql("UPDATE `psa_template_page` set `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint2 - NavLight' where `TEMPLATE_PAGE_ID`=1015");
        $this->addSql("UPDATE `psa_template_page` set `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint2 - Dealer Locator' where `TEMPLATE_PAGE_ID`=1518");
        $this->addSql("DELETE FROM `psa_template_page` where `TEMPLATE_PAGE_ID`=1530");
        $this->addSql("DELETE FROM `psa_template_page_area` where TEMPLATE_PAGE_ID=1530");

        //NavFull
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('5028', 'PF11', '1013', '150', '812', '11', '11', NULL, NULL, '-2')");
        $this->addSql("DELETE FROM `psa_zone_template` where `ZONE_TEMPLATE_ID` IN (6060,6061,6062)");

        //NavLight
        $this->addSql("DELETE FROM `psa_zone_template` where `ZONE_TEMPLATE_ID` IN (6063,6064,6065,6066,6067)");
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('5040', 'PF11', '1015', '150', '812', '13', '13', NULL, NULL, '-2')");
        $this->addSql("UPDATE `psa_zone_template` set ZONE_TEMPLATE_ORDER=5 , ZONE_TEMPLATE_MOBILE_ORDER=5 WHERE ZONE_TEMPLATE_ID=5035");

        //HP
        $this->addSql("DELETE FROM `psa_zone_template` where `ZONE_TEMPLATE_ID` IN (6068,6069,6070,6071,6072,6073,6074,6075,6076,6077)");
    }
}
