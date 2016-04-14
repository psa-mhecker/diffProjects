<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150826104239 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        /**
         * Creation Garbatit 09 car selector
         */
        // ajout Type de gabarit
        $this->addSql("INSERT INTO `psa_page_type` VALUES (60, 'NDP - Forms', 'G37', NULL, NULL, NULL,'', '', NULL) ");
        // ajout du gabarit
        $this->addSql("INSERT INTO `psa_template_page` VALUES (385, 2, 60, 'NDP_TP_FORMS', NULL) ");
        // creation zone
        $this->addSql("INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID, TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR, HAUTEUR, IS_DROPPABLE) VALUES
              (385, 10 , 1, 1, 1, 4, 1, 0),  /* Navigation */
              (385, 121, 2, 2, 1, 4, 1, 0), /* Header */
              (385, 148, 3, 3, 1, 4, 1, 0), /* Corps de page */
              (385, 122, 4, 4, 1, 4, 1, 0)  /* Footer */
        ");
        // ajout des blocs dans les zones
       $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
          (4800, 'NDP_PT21_NAVIGATION', 385, 10 , 798, 1, NULL, NULL, NULL, 30), /* Area: Navigation */
          (4801, 'NDP_PN7_ENTETE'     , 385, 121, 791, 2, NULL, NULL, NULL, 30), /* Area: Header */
          (4802, 'NDP_PF17_FORM'      , 385, 148, 837, 3, NULL, NULL, NULL, 30), /* Area: Corps de page */
          (4803, 'NDP_PT2_FOOTER'     , 385, 122, 800, 4, NULL, NULL, NULL, 30)  /* Area: Footer */
          ");


        // Traduction
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_TP_FORMS', null, 2, null, null, 1, null)
              ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_TP_FORMS', 1, 1, 'NDP - Forms')
              ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // supression gabarit 09 car selector
        $this->addSql("DELETE FROM `psa_zone_template` WHERE TEMPLATE_PAGE_ID = 385");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE TEMPLATE_PAGE_ID = 385");
        $this->addSql("DELETE FROM `psa_template_page` WHERE  TEMPLATE_PAGE_ID = 385");
        $this->addSql("DELETE FROM `psa_page_type` WHERE PAGE_TYPE_ID =  60");

        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_TP_FORMS"
                )'
            );
        }

    }
}
