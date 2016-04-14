<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151214164826 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //clean Gabarits Sprint4
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint4 - NavFull' where TEMPLATE_PAGE_ID=1013");
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - TOUS Sprint - NavLight SHOWROOM' where TEMPLATE_PAGE_ID=1015");
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint4 - Dealer Locator' where TEMPLATE_PAGE_ID=1518");
        $this->addSql("DELETE FROM `psa_zone_template` where `ZONE_TEMPLATE_ID` IN (6047,6048,6049,6050,6051)");
        //1013
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='18', `ZONE_TEMPLATE_MOBILE_ORDER` ='18' where `ZONE_TEMPLATE_ID` = 5046");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='15', `ZONE_TEMPLATE_MOBILE_ORDER` ='15' where `ZONE_TEMPLATE_ID` = 5030");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='16', `ZONE_TEMPLATE_MOBILE_ORDER` ='16' where `ZONE_TEMPLATE_ID` = 5012");

        //1530
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='14', `ZONE_TEMPLATE_MOBILE_ORDER` ='14' where `ZONE_TEMPLATE_ID` = 6077");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='10', `ZONE_TEMPLATE_MOBILE_ORDER` ='10' where `ZONE_TEMPLATE_ID` = 6074");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='11', `ZONE_TEMPLATE_MOBILE_ORDER` ='11' where `ZONE_TEMPLATE_ID` = 6072");
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('6079', 'NDP_PF6_DRAG_DROP', '1530', '150', '786', '12', '12', NULL, NULL, '-2'),
                        ('6080', 'NDP_PC59_TOOLS', '1530', '150', '777', '13', '13', NULL, NULL, '-2')");

        //1015
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='25', `ZONE_TEMPLATE_MOBILE_ORDER` ='25' where `ZONE_TEMPLATE_ID` = 5045");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='14', `ZONE_TEMPLATE_MOBILE_ORDER` ='14' where `ZONE_TEMPLATE_ID` = 6078");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='15', `ZONE_TEMPLATE_MOBILE_ORDER` ='15' where `ZONE_TEMPLATE_ID` = 5017");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_MOBILE_ORDER` ='2' where `ZONE_TEMPLATE_ID` = 6063");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_MOBILE_ORDER` ='4' where `ZONE_TEMPLATE_ID` = 5020");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_MOBILE_ORDER` ='5' where `ZONE_TEMPLATE_ID` = 5034");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_MOBILE_ORDER` ='16' where `ZONE_TEMPLATE_ID` = 6064");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_MOBILE_ORDER` ='17' where `ZONE_TEMPLATE_ID` = 6065");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_MOBILE_ORDER` ='18' where `ZONE_TEMPLATE_ID` = 6066");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_MOBILE_ORDER` ='19' where `ZONE_TEMPLATE_ID` = 6067");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_MOBILE_ORDER` ='20' where `ZONE_TEMPLATE_ID` = 999");
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('6081', 'NDP_PN7_ENTETE', '1015', '151', '791', '3', '3', NULL, NULL, '-2'),
                        ('6082', 'NDP_PF6_DRAG_DROP', '1015', '150', '786', '21', '21', NULL, NULL, '-2'),
                        ('6083', 'NDP_PC59_TOOLS', '1015', '150', '777', '22', '22', NULL, NULL, '-2'),
                        ('6084', 'NDP_PN21_FULL_USP', '1015', '150', '825', '23', '23', NULL, NULL, '-2'),
                        ('6085', 'NDP_PC77_DIMENSION_VEHICULE', '1015', '150', '758', '24', '24', NULL, NULL, '-2')");

        //Type Gabarits G21, G22
        $this->addSql("INSERT INTO `psa_page_type` (PAGE_TYPE_ID, PAGE_TYPE_LABEL, PAGE_TYPE_CODE, PAGE_TYPE_UNIQUE, PAGE_TYPE_ONE_USE, PAGE_TYPE_HIDE, PAGE_TYPE_PARAM, PAGE_TYPE_SHORTCUT, PAGE_TYPE_ORDER) VALUES
                        ('104', 'NDP - APV', 'G21', NULL, NULL, NULL, '', '', NULL),
                        ('105', 'NDP - APV prestation', 'G22', NULL, NULL, NULL, '', '', NULL)");
        //Gabarits APV
        $this->addSql("INSERT INTO `psa_template_page` (`TEMPLATE_PAGE_ID`, `SITE_ID`, `PAGE_TYPE_ID`, `TEMPLATE_PAGE_LABEL`) VALUES
                        (1531,2,104,'AGILE - DEMO - Sprint4 - APV prestation (ex : G22)'),
                        (1532,2,105,'AGILE - DEMO - Sprint4 - APV (ex : G21)')");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`,`AREA_ID`,`TEMPLATE_PAGE_AREA_ORDER`,`LIGNE`,`COLONNE`,`LARGEUR`,`HAUTEUR`,`IS_DROPPABLE`) VALUES
                        (1531,10,1,1,1,4,1,0),
                        (1531,121,2,2,1,4,1,0),
                        (1531,122,4,4,1,4,1,0),
                        (1531,150,3,3,1,4,1,0),
                        (1532,10,1,1,1,4,1,0),
                        (1532,121,2,2,1,4,1,0),
                        (1532,122,4,4,1,4,1,0),
                        (1532,150,3,3,1,4,1,0)");
        //Tranches PC52, PC53
        $this->addSql("INSERT INTO `psa_zone`(ZONE_ID, ZONE_TYPE_ID, ZONE_LABEL, ZONE_FREE, ZONE_COMMENT, ZONE_BO_PATH, ZONE_FO_PATH, ZONE_IFRAME, ZONE_AJAX, ZONE_PROGRAM, ZONE_DB_MULTI, ZONE_IMAGE, ZONE_CATEGORY_ID, ZONE_CONTENT, PLUGIN_ID) VALUES
                        ('907', '1', 'NDP_PC52_RESULTATS_APV', '0', NULL, 'Cms_Page_Ndp_Pc52ResultatsApv', 'Pc52ResultatsApv', '0', '0', '0', NULL, NULL, '28', '0', ''),
                        ('908', '1', 'NDP_PC53_FICHE_PRESTATION_APV', '0', NULL, 'Cms_Page_Ndp_Pc53FichePrestationApv', 'Pc53FichePrestationApv', '0', '0', '0', NULL, NULL, '28', '0', '')");
        //AGILE – DEMO – SPRINT4 –APV prestation (ex : G22)
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('6086', 'NDP_PT21_NAVIGATION', '1531', '10', '798', '1', '1', NULL, NULL, '-2'),
                        ('6087', 'NDP_PN7_ENTETE', '1531', '121', '791', '2', '2', NULL, NULL, '-2'),
                        ('6088', 'PT2', '1531', '122', '800', '10', '10', NULL, NULL, '-2'),
                        ('6089', 'NDP_PC8_DEUX_COLONNES_TEXTE', '1531', '150', '751', '5', '5', NULL, NULL, '-2'),
                        ('6090', 'NDP_PC53_FICHE_PRESTATION_APV', '1531', '150', '908', '6', '6', NULL, NULL, '-2')");
        //AGILE – DEMO – SPRINT4 –APV (ex : G21)
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('6091', 'NDP_PT21_NAVIGATION', '1532', '10', '798', '1', '1', NULL, NULL, '-2'),
                        ('6092', 'NDP_PN7_ENTETE', '1532', '121', '791', '2', '2', NULL, NULL, '-2'),
                        ('6093', 'PT2', '1532', '122', '800', '10', '10', NULL, NULL, '-2'),
                        ('6094', 'NDP_PC5_UNE_COLONNE', '1532', '150', '776', '5', '5', NULL, NULL, '-2'),
                        ('6095', 'NDP_PC52_RESULTATS_APV', '1532', '150', '908', '6', '6', NULL, NULL, '-2')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint3 - NavFull' where TEMPLATE_PAGE_ID=1013");
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint3 - NavLight' where TEMPLATE_PAGE_ID=1015");
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL`='AGILE - DEMO - Sprint3 - Dealer Locator' where TEMPLATE_PAGE_ID=1518");

        //1013
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = '15', `ZONE_TEMPLATE_MOBILE_ORDER` = '12' where `ZONE_TEMPLATE_ID` = 5046");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='3', `ZONE_TEMPLATE_MOBILE_ORDER` ='3' where `ZONE_TEMPLATE_ID` = 5030");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='4', `ZONE_TEMPLATE_MOBILE_ORDER` ='4' where `ZONE_TEMPLATE_ID` = 5012");

        //1530
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='10', `ZONE_TEMPLATE_MOBILE_ORDER` ='10' where `ZONE_TEMPLATE_ID` = 6077");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='2', `ZONE_TEMPLATE_MOBILE_ORDER` ='2' where `ZONE_TEMPLATE_ID` = 6074");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='3', `ZONE_TEMPLATE_MOBILE_ORDER` ='3' where `ZONE_TEMPLATE_ID` = 6072");
        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` IN (6079,6080)");

        //1015
        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` IN (6081,6082,6083,6084,6085)");

        //APV
        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` IN (6086,6087,6088,6089,6090,6091,6092,6093,6094,6095)");
        $this->addSql("DELETE FROM `psa_zone` WHERE `ZONE_ID` in (907,908)");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE `TEMPLATE_PAGE_ID` in (1531,1532)");
        $this->addSql("DELETE FROM `psa_template_page` WHERE `TEMPLATE_PAGE_ID` in (1531,1532)");
        $this->addSql("DELETE FROM `psa_page_type` WHERE `PAGE_TYPE_ID` IN (104,105)");

    }
}
