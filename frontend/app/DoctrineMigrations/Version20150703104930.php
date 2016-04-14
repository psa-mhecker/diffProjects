<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150703104930 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        /**
         *
         * Creation Garbatit 27 ShowRoom
         */
        // ajout Type de gabarit
        $this->addSql("INSERT INTO `psa_page_type` VALUES (33,'NDP - Showroom','G27',1,NULL,NULL,'','',NULL) ");
        // ajout du gabarit
        $this->addSql("INSERT INTO `psa_template_page` VALUES (378,2,33,'NDP_TP_SHOWROOM',NULL) ");
        // creation zone du gabarit
        $this->addSql("INSERT INTO `psa_template_page_area` VALUES
                  (378, 10,1,1,1,4,1,0),
                  (378,121,2,2,1,4,1,0),
                  (378,150,3,3,1,4,1,0),
                  (378,122,4,4,1,4,1,0)
                  ");
        // creation des 3  nouveau bloc pc85, pc95, pt23
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
                (834, 1, 'NDP_PC85_REVOO', 0, NULL, 'Cms_Page_Ndp_Pc85Revoo','Pc85RevooStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')
            ");
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
                (835, 1, 'NDP_PC95_INTERESTED_BY', 0, NULL, 'Cms_Page_Ndp_Pc95InterestedBy','Pc95InterestedByStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')
            ");

        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
                (836, 1, 'NDP_PT23_MODULE_QUALIFICATION', 0, NULL, 'Cms_Page_Ndp_Pt23ModuleQualification','Pt23ModuleQualificationStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')
            ");

        // ajout des blocs dans les zones
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4458,'NDP_PT21_NAVIGATION'                        ,378, 10 , 798, 1 , 1 , NULL, NULL, 30),

              (4459, 'NDP_PN15_CONFISHOW_HEADER'                 ,378, 121, 823, 2 , 2 , NULL, NULL, 30),
              (4460, 'NDP_PN7_ENTETE'                            ,378, 121, 791, 3 , 3 , NULL, NULL, 30),
              (4461, 'NPD_PT22_MY_PEUGEOT'                       ,378, 121, 826, 4 , 4 , NULL, NULL, 30),
              (4462, 'NPD_PT3_JE_VEUX'                           ,378, 121, 801, 5 , 5 , NULL, NULL, 30),
              (4463, 'NDP_PF2_PRESENTATION_SHOWROOM'             ,378, 121, 830, 6 , 6 , NULL, NULL, 30),
              (4464, 'NDP_PN14_CONFISHOW_NAVIGATION'             ,378, 121, 822, 7 , 7 , NULL, NULL, 30),

              (4465, 'NDP_PN21_FULL_USP'                         ,378, 150, 825, 8 , 8 , NULL, NULL,30),
              (4466, 'NDP_PF14_RESEAUX_SOCIAUX'                  ,378, 150, 759, 9 , 9 , NULL, NULL,30),
              (4467, 'NDP_PC40_CTA'                              ,378, 150, 771, 10, 10, NULL, NULL,30),
              (4468, 'NDP_PC5_UNE_COLONNE'                       ,378, 150, 776, 11, 11, NULL, NULL,30),
              (4469, 'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS' ,378, 150, 766, 12, 12, NULL, NULL,30),
              (4470, 'NDP_PC85_REVOO'                            ,378, 150, 834, 13, 13, NULL, NULL,30),
              (4471, 'NDP_PC58_CONTACT'                          ,378, 150, 757, 14, 14, NULL, NULL,30),
              (4472, 'NDP_PC79_LIGHT_MEDIA_WALL'                 ,378, 150, 816, 15, 15, NULL, NULL,30),
              (4473, 'NDP_PF11_RECHERCHE_POINT_DE_VENTE'         ,378, 150, 812, 16, 16, NULL, NULL,30),
              (4474, 'NDP_PC33_OFFRE_PLUS'                       ,378, 150, 769, 17, 17, NULL, NULL,30),
              (4475, 'NDP_PC95_INTERESTED_BY'                    ,378, 150, 835, 18, 18, NULL, NULL,30),
              (4476, 'NDP_PC42_ACTUALITES'                       ,378, 150, 773, 19, 19, NULL, NULL,30),
              (4477, 'NDP_PN2_ONGLET'                            ,378, 150, 789, 20, 20, NULL, NULL,30),
              (4478, 'NDP_PF42_SELECTEUR_DE_TEINTE_360'          ,378, 150, 784, 21, 21, NULL, NULL,30),
              (4479, 'NDP_PC60_SUMMARY_AND_SHOWROOM_CTA'         ,378, 150, 818, 22, 22, NULL, NULL,30),
              (4480, 'NDP_PF53_ENGINES'                          ,378, 150, 817, 23, 23, NULL, NULL,30),
              (4481, 'NDP_PC9_UN_ARTICLE_UN_VISUEL'              ,378, 150, 752, 24, 24, NULL, NULL,30),
              (4482, 'NDP_PC77_DIMENSION_VEHICULE'               ,378, 150, 758, 25, 25, NULL, NULL,30),
              (4483, 'NDP_PC23_MUR_MEDIA'                        ,378, 150, 802, 26, 26, NULL, NULL,30),
              (4484, 'NDP_PC83_ACCESSORIES_CONTENT'              ,378, 150, 820, 27, 27, NULL, NULL,30),
              (4485, 'NDP_PN3_TOGGLE_ACCORDEON'                  ,378, 150, 790, 28, 28, NULL, NULL,30),
              (4486, 'NDP_PC7_DEUX_COLONNES'                     ,378, 150, 781, 29, 29, NULL, NULL,30),
              (4487, 'NDP_PC16_VERBATIM'                         ,378, 150, 768, 30, 30, NULL, NULL,30),
              (4488, 'NDP_PC12_3_COLONNES'                       ,378, 150, 760, 31, 31, NULL, NULL,30),
              (4489, 'NDP_PC69_DEUX_COLONNES'                    ,378, 150, 767, 32, 32, NULL, NULL,30),
              (4490, 'NDP_PC78_MOSAIC_USP'                       ,378, 150, 819, 33, 33, NULL, NULL,30),
              (4491, 'NDP_PT23_MODULE_QUALIFICATION'             ,378, 150, 836, 34, 34, NULL, NULL,30),
              (4492, 'NDP_PF6_DRAG_DROP'                         ,378, 150, 786, 35, 35, NULL, NULL,30),
              (4493, 'NDP_PN18_IFRAME'                           ,378, 150, 824, 36, 36, NULL, NULL,30),

              (4494, 'NPD_PT2_FOOTER'                            ,378, 122, 800, 37, 37, NULL, NULL,30)
              ");

        // Changer la tradu de NDP_PC42_ACTUALITES

        $this->addSql('UPDATE psa_label_langue_site SET LABEL_ID="NDP_PC42_ACTUALITES"  WHERE LABEL_ID = "NDP_PC42_Actualites"');
        $this->addSql('UPDATE psa_label SET LABEL_ID="NDP_PC42_ACTUALITES"  WHERE LABEL_ID = "NDP_PC42_Actualites"');
        // ajout trad nouvelle tranche
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_TP_SHOWROOM", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_PC85_REVOO", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_PC95_INTERESTED_BY", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_PT23_MODULE_QUALIFICATION", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_TP_SHOWROOM", 1, 1, "NDP - Showroom"),
            ("NDP_PC85_REVOO", 1, 1, "PC85 Customer review reevoo_dynamic content"),
            ("NDP_PC95_INTERESTED_BY", 1, 1, "PC95 Interested By"),
            ("NDP_PT23_MODULE_QUALIFICATION", 1, 1, "PT23 Personalization tab")'
        );


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        // supression des l'association des blocs
        $this->addSql('DELETE FROM psa_zone_template WHERE ZONE_TEMPLATE_ID >= 4458 AND  ZONE_TEMPLATE_ID <= 4494 ');
        // supression des nouvelles zones
        $this->addSql('DELETE FROM psa_zone WHERE ZONE_ID  IN (834,835,836) ');
        // supression des area
        $this->addSql('DELETE FROM psa_template_page_area WHERE TEMPLATE_PAGE_ID  = 378 ');
        // supression du template
        $this->addSql("DELETE  FROM `psa_template_page` WHERE TEMPLATE_PAGE_ID  = 378 ");
        //supression du type de gabarit
        $this->addSql("DELETE  FROM `psa_page_type` WHERE PAGE_TYPE_ID  = 33 ");

        //supression des trads
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_TP_SHOWROOM",
                    "NDP_PC85_REVOO",
                    "NDP_PC95_INTERESTED_BY",
                    "NDP_PT23_MODULE_QUALIFICATION"
                )'
            );
        }
    }
}
