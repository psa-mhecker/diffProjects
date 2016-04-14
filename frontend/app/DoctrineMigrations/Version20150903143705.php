<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150903143705 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        // PN 14 AUTO :
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (900, 2, 'NDP_PN14_CONFISHOW_NAVIGATION', 0, NULL, NULL, 'Pn14NavigationConfishowStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')");
        //G36 - Technologie
        $this->addSql("INSERT INTO `psa_page_type` VALUES (100, 'NDP - Technologie', 'G36', NULL, NULL, NULL,'', '', NULL) ");
        $this->addSql("INSERT INTO `psa_template_page` VALUES (1001, 2, 100,'NDP_TP_TECHNO', NULL)");
        $this->addSql("INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID, TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR, HAUTEUR, IS_DROPPABLE) VALUES
              (1001, 10 , 1, 1, 1, 4, 1, 0),  /* Navigation */
              (1001, 121, 2, 2, 1, 4, 1, 0), /* Header */
              (1001, 148, 3, 3, 1, 4, 1, 0), /* Corps de page */
              (1001, 150, 4, 4, 1, 4, 1, 0), /* Zone Dynamique */
              (1001, 122, 5, 5, 1, 4, 1, 0)  /* Footer */
        ");
        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
          (4913, 'NDP_PT21_NAVIGATION', 1001, 10 , 798, 1, NULL, NULL, NULL, 30), 
          
          (4914, 'NDP_PN7_ENTETE'     , 1001, 121, 791, 2, NULL, NULL, NULL, 30), 
          (4915, 'NDP_PT22_MY_PEUGEOT', 1001, 121 , 826, 3, NULL, NULL, NULL, 30),
          (4916, 'NDP_PT3_JE_VEUX'    , 1001, 121 , 801, 4, NULL, NULL, NULL, 30), 
          
          (4917, 'NDP_PC18_CONTENU_GRAND_VISUEL', 1001, 148, 753, 5, NULL, NULL, NULL, 30),
          (4918, 'NDP_PN14_CONFISHOW_NAVIGATION', 1001, 148, 900, 6, NULL, NULL, NULL, 30), 
          
          (4919, 'NDP_PN21_FULL_USP'      , 1001, 150, 825, 7, NULL, NULL, NULL, 30), 
          (4920, 'NDP_PC5_UNE_COLONNE'      , 1001, 150, 776, 8, NULL, NULL, NULL, 30), 
          (4921, 'NDP_PC7_DEUX_COLONNES'      , 1001, 150, 781, 9, NULL, NULL, NULL, 30), 
          (4922, 'NDP_PC9_UN_ARTICLE_UN_VISUEL'      , 1001, 150, 752, 10, NULL, NULL, NULL, 30), 
          (4923, 'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS'      , 1001, 150, 766, 11, NULL, NULL, NULL, 30), 
          (4924, 'NDP_PC12_3_COLONNES'      , 1001, 150, 760, 12, NULL, NULL, NULL, 30), 
          (4925, 'NDP_PC79_LIGHT_MEDIA_WALL'      , 1001, 150, 816, 14, NULL, NULL, NULL, 30), 
          (4926, 'NDP_PC23_MUR_MEDIA'      , 1001, 150, 802, 15, NULL, NULL, NULL, 30), 
          (4927, 'NDP_PC78_MOSAIC_USP'      , 1001, 150, 819, 16, NULL, NULL, NULL, 30), 
          (4928, 'NDP_PF6_DRAG_DROP'      , 1001, 150, 786, 17, NULL, NULL, NULL, 30), 
          (4929, 'NDP_PN18_IFRAME'      , 1001, 150, 824, 18, NULL, NULL, NULL, 30), 
          (4930, 'NDP_PC73_MEGA_BANNIERE_DYNAMIQUE'      , 1001, 150, 829, 19, NULL, NULL, NULL, 30), 
          (4931, 'NDP_PC2_CONTENU_TEXTE_RICHE'      , 1001, 150, 750, 20, NULL, NULL, NULL, 30), 
          (4932, 'NDP_PC69_DEUX_COLONNES'      , 1001, 150, 767, 21, NULL, NULL, NULL, 30), 
          (4933, 'NDP_PC18_CONTENU_GRAND_VISUEL'      , 1001, 150, 753, 22, NULL, NULL, NULL, 30), 
          (4934, 'NDP_PC58_CONTACT'      , 1001, 150, 757, 23, NULL, NULL, NULL, 30), 
          (4935, 'NDP_PC40_CTA'      , 1001, 150, 771, 24, NULL, NULL, NULL, 30), 
          (4936, 'NDP_PC33_OFFRE_PLUS'      , 1001, 150, 769, 25, NULL, NULL, NULL, 30), 
          (4937, 'NDP_PC16_VERBATIM'      , 1001, 150, 768, 26, NULL, NULL, NULL, 30), 
          
          (4938, 'NDP_PT2_FOOTER'     , 1001, 122, 800, 27, NULL, NULL, NULL, 30) 
          ");
                
        // Traduction
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_TP_TECHNO', null, 2, null, null, 1, null)
              ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_TP_TECHNO', 1, 1, 'Technologie')
              ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        // supression gabarit 36 Technologie
        $this->addSql("DELETE FROM `psa_zone_template` WHERE TEMPLATE_PAGE_ID = 1001");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE TEMPLATE_PAGE_ID = 1001");
        $this->addSql("DELETE FROM `psa_template_page` WHERE  TEMPLATE_PAGE_ID = 1001");
        $this->addSql("DELETE FROM `psa_page_type` WHERE PAGE_TYPE_ID =  100");
        $this->addSql("DELETE FROM `psa_zone` WHERE ZONE_ID = 900");
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_TP_TECHNO"
                )'
            );
        }
    }
}
