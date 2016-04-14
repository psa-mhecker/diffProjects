<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150903093804 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        //G34 - Contact
        $this->addSql('UPDATE psa_page_type set PAGE_TYPE_LABEL = "Contact", PAGE_TYPE_CODE = "G34", PAGE_TYPE_SHORTCUT = "" WHERE PAGE_TYPE_ID = 5');
        $this->addSql("INSERT INTO `psa_template_page` VALUES (1000, 2, 5,'NDP_TP_CONTACT', NULL)");
        $this->addSql("INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID, TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR, HAUTEUR, IS_DROPPABLE) VALUES
              (1000, 10 , 1, 1, 1, 4, 1, 0),  /* Navigation */
              (1000, 121, 2, 2, 1, 4, 1, 0), /* Header */
              (1000, 148, 3, 3, 1, 4, 1, 0), /* Corps de page */
              (1000, 150, 4, 4, 1, 4, 1, 0), /* Zone Dynamique */
              (1000, 122, 5, 5, 1, 4, 1, 0)  /* Footer */
        ");
        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
          (4901, 'NDP_PT21_NAVIGATION', 1000, 10 , 798, 1, NULL, NULL, NULL, 30), 
          
          (4902, 'NDP_PT22_MY_PEUGEOT', 1000, 121 , 826, 2, NULL, NULL, NULL, 30),
          (4903, 'NDP_PT3_JE_VEUX'    , 1000, 121 , 801, 3, NULL, NULL, NULL, 30), 
          (4904, 'NDP_PN7_ENTETE'     , 1000, 121, 791, 4, NULL, NULL, NULL, 30), 
          
          (4905, 'NDP_PF16_AUTRES_RESEAUX_SOCIAUX', 1000, 148, 786, 5, NULL, NULL, NULL, 30),
          (4906, 'NDP_PN2_ONGLET'      , 1000, 148, 789, 6, NULL, NULL, NULL, 30), 
          
          (4907, 'NDP_PC40_CTA'      , 1000, 150, 771, 7, NULL, NULL, NULL, 30), 
          (4908, 'NDP_PC12_3_COLONNES'      , 1000, 150, 760, 8, NULL, NULL, NULL, 30), 
          (4909, 'NDP_PN3_TOGGLE_ACCORDEON'      , 1000, 150, 790, 9, NULL, NULL, NULL, 30), 
          (4910, 'NDP_PC59_TOOLS'      , 1000, 150, 777, 10, NULL, NULL, NULL, 30), 
          (4911, 'NDP_PC2_CONTENU_TEXTE_RICHE'      , 1000, 150, 750, 11, NULL, NULL, NULL, 30), 
          
          (4912, 'NDP_PT2_FOOTER'     , 1000, 122, 800, 12, NULL, NULL, NULL, 30) 
          ");
                
        // Traduction
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_TP_CONTACT', null, 2, null, null, 1, null)
              ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_TP_CONTACT', 1, 1, 'Contact')
              ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_zone_template` WHERE TEMPLATE_PAGE_ID = 1000");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE TEMPLATE_PAGE_ID = 1000");
        $this->addSql("DELETE FROM `psa_template_page` WHERE  TEMPLATE_PAGE_ID = 1000");
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_TP_CONTACT"
                )'
            );
        }
    }
}
