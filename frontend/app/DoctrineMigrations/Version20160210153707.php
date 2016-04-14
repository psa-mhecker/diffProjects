<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160210153707 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_template_page` SET `PAGE_TYPE_ID` = 33 WHERE `TEMPLATE_PAGE_ID` = 1533");
        $this->addSql("UPDATE `psa_page_type` SET `PAGE_TYPE_UNIQUE` = NULL, `PAGE_TYPE_ONE_USE` = NULL WHERE `PAGE_TYPE_ID` = 32");

        //AGILE - NDP_TP_CAR_SELECTOR
        $this->addSql("INSERT INTO `psa_template_page` (`TEMPLATE_PAGE_ID`, `SITE_ID`, `PAGE_TYPE_ID`, `TEMPLATE_PAGE_LABEL`) VALUES
            (1538,2,32,'AGILE - NDP_TP_CAR_SELECTOR')");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`,`AREA_ID`,`TEMPLATE_PAGE_AREA_ORDER`,`LIGNE`,`COLONNE`,`LARGEUR`,`HAUTEUR`,`IS_DROPPABLE`) VALUES
            (1538,10,1,1,1,4,1,NULL),
            (1538,121,2,2,1,4,1,0),
            (1538,122,4,4,1,4,1,0),
            (1538,150,3,3,1,4,1,0)");
        $this->addSql("INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`,`ZONE_TEMPLATE_LABEL`,`TEMPLATE_PAGE_ID`,`AREA_ID`,`ZONE_ID`,`ZONE_TEMPLATE_ORDER`,`ZONE_TEMPLATE_MOBILE_ORDER`,`ZONE_TEMPLATE_TABLET_ORDER`,`ZONE_TEMPLATE_TV_ORDER`,`ZONE_CACHE_TIME`) VALUES
            (6242,'NDP_PT21_NAVIGATION',1538,10,798,1,1,NULL,NULL,-2),
            (6243,'NDP_PN7_ENTETE',1538,121,791,2,2,NULL,NULL,-2),
            (6244,'NDP_PF25_FILTRES_RESULTATS_CAR_SELECTOR',1538,150,813,3,3,NULL,NULL,-2),
            (6245,'NDP_PC5_UNE_COLONNE',1538,150,776,4,4,NULL,NULL,-2),
            (6246,'NDP_PT2_FOOTER',1538,122,800,5,5,NULL,NULL,-2);");

        //AGILE - NDP_TP_TECHNO
        $this->addSql("INSERT INTO `psa_template_page` (`TEMPLATE_PAGE_ID`, `SITE_ID`, `PAGE_TYPE_ID`, `TEMPLATE_PAGE_LABEL`) VALUES
            (1539,2,100,'AGILE - NDP_TP_TECHNO')");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`,`AREA_ID`,`TEMPLATE_PAGE_AREA_ORDER`,`LIGNE`,`COLONNE`,`LARGEUR`,`HAUTEUR`,`IS_DROPPABLE`) VALUES
            (1539,10,1,1,1,4,1,0),
            (1539,121,2,2,1,4,1,0),
            (1539,122,5,5,1,4,1,0),
            (1539,148,3,3,1,4,1,0),
            (1539,150,4,4,1,4,1,0)");
        $this->addSql("INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`,`ZONE_TEMPLATE_LABEL`,`TEMPLATE_PAGE_ID`,`AREA_ID`,`ZONE_ID`,`ZONE_TEMPLATE_ORDER`,`ZONE_TEMPLATE_MOBILE_ORDER`,`ZONE_TEMPLATE_TABLET_ORDER`,`ZONE_TEMPLATE_TV_ORDER`,`ZONE_CACHE_TIME`) VALUES
            (6247,'NDP_PT21_NAVIGATION',1539,10,798,1,1,NULL,NULL,-2),
            (6248,'NDP_PN7_ENTETE',1539,121,791,2,2,NULL,NULL,-2),
            (6249,'NDP_PN13_ANCRES',1539,148,788,3,3,NULL,NULL,-2),
            (6250,'NDP_PN14_CONFISHOW_NAVIGATION',1539,148,900,4,4,NULL,NULL,-2),
            (6251,'NDP_PC5_UNE_COLONNE',1539,150,776,5,5,NULL,NULL,-2),
            (6252,'NDP_PC7_DEUX_COLONNES',1539,150,781,6,6,NULL,NULL,-2),
            (6253,'NDP_PC9_UN_ARTICLE_UN_VISUEL',1539,150,752,7,7,NULL,NULL,-2),
            (6254,'NDP_PC12_3_COLONNES',1539,150,760,8,8,NULL,NULL,-2),
            (6255,'NDP_PC23_MUR_MEDIA',1539,150,802,9,9,NULL,NULL,-2),
            (6256,'NDP_PC40_CTA',1539,150,771,10,10,NULL,NULL,-2),
            (6257,'NDP_PC79_LIGHT_MEDIA_WALL',1539,150,816,11,11,NULL,NULL,-2),
            (6258,'NDP_PF6_DRAG_DROP',1539,150,786,12,12,NULL,NULL,-2),
            (6259,'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS',1539,150,766,13,13,NULL,NULL,-2),
            (6260,'NDP_PC69_DEUX_COLONNES',1539,150,767,14,14,NULL,NULL,-2),
            (6261,'NDP_PN18_IFRAME',1539,150,824,15,15,NULL,NULL,-2),
            (6262,'NDP_PT2_FOOTER',1539,122,800,16,16,NULL,NULL,-2)");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_zone_template`  where `TEMPLATE_PAGE_ID` IN (1538,1539)");
        $this->addSql("DELETE FROM `psa_template_page` where `TEMPLATE_PAGE_ID` IN (1538,1539)");
        $this->addSql("DELETE FROM `psa_template_page_area` where TEMPLATE_PAGE_ID IN (1538,1539)");

    }
}
