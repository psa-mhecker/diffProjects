<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150903162703 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        // PC37 :
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (901, 1, 'NDP_PC37_PRESENTATION_FICHE_CONTRAT', 0, NULL, 'Cms_Page_Ndp_Pc37PresentationFicheContrat', 'Pc37PresentationFicheContrat', 0, 0, 0, NULL, NULL, 28, 0, ''),
          
            (902, 2, 'NDP_PF23_RANGE_BAR', 0, NULL, '', 'Pf23RangeBarStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (903, 2, 'NDP_PF27_Car_Picker', 0, NULL, '', 'Pf27CarPickerStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (904, 2, 'NDP_PC43_APPLICATIONS_MOBILES', 0, NULL, '', 'Pc43ApplicationsMobilesStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (905, 2, 'NDP_PF14_RESEAUX_SOCIAUX', 0, NULL, '', 'Pf14ReseauxSociauxStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (906, 2, 'NDP_PF16_AUTRES_RESEAUX_SOCIAUX', 0, NULL, '', 'Pf16AutresReseauxSociauxStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')
            ");
        //G12 - Contenu Basique
        $this->addSql("INSERT INTO `psa_page_type` VALUES (101, 'NDP - Contenu Basique', 'G12', NULL, NULL, NULL,'', '', NULL) ");
        $this->addSql("INSERT INTO `psa_template_page` VALUES (1002, 2, 101,'NDP_TP_CNT_BASIQUE', NULL)");
        $this->addSql("INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID, TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR, HAUTEUR, IS_DROPPABLE) VALUES
              (1002, 10 , 1, 1, 1, 4, 1, 0),  /* Navigation */
              (1002, 121, 2, 2, 1, 4, 1, 0), /* Header */
              (1002, 150, 3, 3, 1, 4, 1, 0), /* Zone Dynamique */
              (1002, 122, 4, 4, 1, 4, 1, 0)  /* Footer */
        ");
        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
          (4939, 'NDP_PT21_NAVIGATION', 1002, 10 , 798, 1, NULL, NULL, NULL, 30), 
           
          (4940, 'NDP_PT22_MY_PEUGEOT', 1002, 121 , 826, 2, NULL, NULL, NULL, 30),
          (4941, 'NDP_PT3_JE_VEUX'    , 1002, 121 , 801, 3, NULL, NULL, NULL, 30), 
          (4942, 'NDP_PN7_ENTETE'     , 1002, 121, 791, 4, NULL, NULL, NULL, 30),

          (4943, 'NDP_PC5_UNE_COLONNE'      , 1002, 150, 776, 5, NULL, NULL, NULL, 30), 
          (4944, 'NDP_PC9_UN_ARTICLE_UN_VISUEL'      , 1002, 150, 752, 6, NULL, NULL, NULL, 30), 
          (4945, 'NDP_PC59_TOOLS'      , 1002, 150, 777, 7, NULL, NULL, NULL, 30), 
          (4546, 'NDP_PT19_ENGAGEMENTS', 1002, 150, 795, 8 , NULL, NULL, NULL, 30),
          (4947, 'NDP_PN2_ONGLET'      , 1002, 150, 789, 9, NULL, NULL, NULL, 30), 
          (4948, 'NDP_PN3_TOGGLE_ACCORDEON'      , 1002, 150, 790, 10, NULL, NULL, NULL, 30), 
          (4549, 'NDP_PN13_ANCRES', 1002, 150, 788,11 , NULL, NULL, NULL, 30),
          (4950, 'NDP_PC2_CONTENU_TEXTE_RICHE'      , 1002, 150, 750, 12, NULL, NULL, NULL, 30), 
          (4951, 'NDP_PC7_DEUX_COLONNES'      , 1002, 150, 781, 13, NULL, NULL, NULL, 30), 
          (4952, 'NDP_PC8_DEUX_COLONNES_TEXTE', 1002, 150, 751,14 , NULL, NULL, NULL, 30),
          
          (4953, 'NDP_PC12_3_COLONNES'      , 1002, 150, 760, 15, NULL, NULL, NULL, 30), 
          (4954, 'NDP_PC16_VERBATIM'      , 1002, 150, 768, 16, NULL, NULL, NULL, 30), 
          (4955, 'NDP_PC18_CONTENU_GRAND_VISUEL', 1002, 150, 753, 17, NULL, NULL, NULL, 30),
          (4956, 'NDP_PC23_MUR_MEDIA'      , 1002, 150, 802, 18, NULL, NULL, NULL, 30), 
          (4957, 'NDP_PC33_OFFRE_PLUS'      , 1002, 150, 769, 19, NULL, NULL, NULL, 30), 
          (4958, 'NDP_PC39_SLIDESHOW_OFFRE',1002,150,756,20,NULL,NULL,NULL,30),
          (4959, 'NDP_PC40_CTA'      , 1002, 150, 771, 21, NULL, NULL, NULL, 30), 
          (4960, 'NDP_PC58_CONTACT'      , 1002, 150, 757, 22, NULL, NULL, NULL, 30), 
          (4961, 'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS'      , 1002, 150, 766, 23, NULL, NULL, NULL, 30), 
          (4962, 'NDP_PC69_DEUX_COLONNES'      , 1002, 150, 767, 24, NULL, NULL, NULL, 30),
          (4963, 'NDP_PF6_DRAG_DROP'      , 1002, 150, 786, 25, NULL, NULL, NULL, 30), 
          (4964, 'NDP_PF8_Webstore_Vehicule_Neuf', 1002, 150, 787,26 , NULL, NULL, NULL, 30),
          (4965, 'NDP_PF11_RECHERCHE_POINT_DE_VENTE'         ,1002, 150, 812, 27, NULL, NULL, NULL,30),
          (4966,'NDP_PF23_RANGE_BAR',1002,150,902,28,NULL, NULL,NULL,30),
          (4967, 'NDP_PF27_Car_Picker', 1002, 150, 903,29 , NULL, NULL, NULL, 30), 
          (4968, 'NDP_PC36_FAQ', 1002, 150, 770,30 , NULL, NULL, NULL, 30),
          (4969, 'NDP_PC37_PRESENTATION_FICHE_CONTRAT', 1002, 150, 901,31 , NULL, NULL, NULL, 30),
          (4970,'NDP_PC41_MENTIONS_JURIDIQUES',1002,150,772,32,NULL,NULL,NULL,30),
          (4971,'NDP_PC43_APPLICATIONS_MOBILES',1002,150,904,33,NULL,NULL,NULL,30), 
          (4972,'NDP_PC62_APPLICATION_BOUSSOLE',1002,150,779,34,NULL,NULL,NULL,30),
          (4973,'NDP_PC63_LIEN_BOUSSOLE',1002,150,780,35,NULL,NULL,NULL,30),
          (4974, 'NDP_PC73_MEGA_BANNIERE_DYNAMIQUE'      , 1002, 150, 829, 36, NULL, NULL, NULL, 30), 
          (4975, 'NDP_PF14_RESEAUX_SOCIAUX'                  ,1002, 150, 905, 37 , NULL , NULL, NULL,30),
          (4976, 'NDP_PC77_DIMENSION_VEHICULE'               ,1002, 150, 758, 38, NULL, NULL, NULL,30),
          (4977, 'NDP_PF16_AUTRES_RESEAUX_SOCIAUX', 1002, 150, 906, 39, NULL, NULL, NULL, 30),
          
          (4978, 'NDP_PT2_FOOTER'     , 1002, 122, 800, 40, NULL, NULL, NULL, 30) 
          ");
                
        // Traduction
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_TP_CNT_BASIQUE', null, 2, null, null, 1, null),
              ('NDP_PC37_PRESENTATION_FICHE_CONTRAT', null, 2, null, null, 1, null)
              ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_TP_CNT_BASIQUE', 1, 1, 'Contenu basique'),
              ('NDP_PC37_PRESENTATION_FICHE_CONTRAT', 1, 1, 'PC37 - List 3 column 9 items_picto square_content')
              ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        // supression gabarit 12 Contenu Basique
        $this->addSql("DELETE FROM `psa_zone_template` WHERE TEMPLATE_PAGE_ID = 1002");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE TEMPLATE_PAGE_ID = 1002");
        $this->addSql("DELETE FROM `psa_template_page` WHERE  TEMPLATE_PAGE_ID = 1002");
        $this->addSql("DELETE FROM `psa_page_type` WHERE PAGE_TYPE_ID =  101");
        $this->addSql("DELETE FROM `psa_zone` WHERE ZONE_ID IN (901, 902, 903, 904, 905, 906)");
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_TP_CNT_BASIQUE", "NDP_PC37_PRESENTATION_FICHE_CONTRAT"
                )'
            );
        }
    }
}
