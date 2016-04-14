<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150904091521 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        //G28 - Master Page Showroom
        $this->addSql("INSERT INTO `psa_page_type` VALUES (102, 'NDP - Master Page Showroom', 'G28', NULL, NULL, NULL,'', '', NULL) ");
        $this->addSql("INSERT INTO `psa_template_page` VALUES (1003, 2, 102,'NDP_TP_MASTER_PAGE_SHOWROOM', NULL)");
        $this->addSql("INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID, TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR, HAUTEUR, IS_DROPPABLE) VALUES
              (1003, 10 , 1, 1, 1, 4, 1, 0),  /* Navigation */
              (1003, 121, 2, 2, 1, 4, 1, 0), /* Header */
              (1003, 150, 3, 3, 1, 4, 1, 0), /* Zone Dynamique */
              (1003, 122, 4, 4, 1, 4, 1, 0)  /* Footer */
        ");
        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
          (4979, 'NDP_PT21_NAVIGATION', 1003, 10 , 798, 1, NULL, NULL, NULL, 30), 
           
          (4980, 'NDP_PT22_MY_PEUGEOT', 1003, 121 , 826, 2, NULL, NULL, NULL, 30),
          (4981, 'NDP_PT3_JE_VEUX'    , 1003, 121 , 801, 3, NULL, NULL, NULL, 30), 
          (4982, 'NDP_PN7_ENTETE'     , 1003, 121, 791, 4, NULL, NULL, NULL, 30),

          (4983, 'NDP_PC5_UNE_COLONNE'      , 1003, 150, 776, 5, NULL, NULL, NULL, 30), 
          (4984, 'NDP_PN2_ONGLET'      , 1003, 150, 789, 6, NULL, NULL, NULL, 30), 
          (4985, 'NDP_PF27_Car_Picker', 1003, 150, 782, 7, NULL, NULL, NULL, 30), 
          (4986, 'NDP_PC95_INTERESTED_BY',1003, 150, 835, 8, NULL, NULL, NULL,30),
          (4987, 'NDP_PC40_CTA'      , 1003, 150, 771, 9, NULL, NULL, NULL, 30), 
          
          (4988, 'NDP_PT2_FOOTER'     , 1003, 122, 800, 10, NULL, NULL, NULL, 30) 
          ");
                
        // Traduction
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_TP_MASTER_PAGE_SHOWROOM', null, 2, null, null, 1, null)
              ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_TP_MASTER_PAGE_SHOWROOM', 1, 1, 'Master Page Showroom')
              ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        // supression gabarit 28 Master Page Showroom
        $this->addSql("DELETE FROM `psa_zone_template` WHERE TEMPLATE_PAGE_ID = 1003");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE TEMPLATE_PAGE_ID = 1003");
        $this->addSql("DELETE FROM `psa_template_page` WHERE  TEMPLATE_PAGE_ID = 1003");
        $this->addSql("DELETE FROM `psa_page_type` WHERE PAGE_TYPE_ID =  102");
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_TP_MASTER_PAGE_SHOWROOM"
                )'
            );
        }
    }
}
