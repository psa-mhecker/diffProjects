<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150804143048 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // SFD BO Gabarits MAJ (NDP-2864)

        // Suppression de la tranche PF44 du Gabarit blanc
        $this->addSql("DELETE FROM psa_zone_template WHERE ZONE_TEMPLATE_ID = 4503");

        // Renommé et modifié le gabarit « G10_Dealer locator » existant en gabarit unique, en le renommant à « G10_Dealer locator – Recherche PDV » et supprimer la tranche PF44.
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE="G10_Dealer locator – Recherche PDV" WHERE LABEL_ID = "NDP_TP_DEALER_LOCATOR"');
        $this->addSql("DELETE FROM psa_zone_template WHERE ZONE_TEMPLATE_ID = 4373");

        // Créé le gabarit unique « G15_Promo APV – Store locator »
        // Créé le gabarit unique « G16_Dealer locator – Devenir Agent »

        // Ajout Type des gabarits
        $this->addSql("INSERT INTO psa_page_type (PAGE_TYPE_ID, PAGE_TYPE_LABEL, PAGE_TYPE_CODE,PAGE_TYPE_UNIQUE,PAGE_TYPE_ONE_USE) VALUES
              (40, 'NDP - Promo APV – Store locator', 'G15',1,1),
              (50, 'NDP - Dealer locator – Devenir Agent', 'G16',1,1)");

        // Ajout des gabarits
        $this->addSql("INSERT INTO psa_template_page (TEMPLATE_PAGE_ID, SITE_ID, PAGE_TYPE_ID, TEMPLATE_PAGE_LABEL, TEMPLATE_PAGE_GENERAL) VALUES
              (381, 2, 40, 'NDP_G15_PROMO_APV_STORE_LOCATOR', NULL),
              (382, 2, 50, 'NDP_G16_DEALER_LOCATOR_DEVENIR_AGENT', NULL)");

        // Creation zone G15
        $this->addSql("INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID, TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR, HAUTEUR, IS_DROPPABLE) VALUES
              (381,10,1,1,1,4,1,0), /* Navigation */
              (381,121,2,2,1,4,1,0), /* Header */
              (381,148,3,3,1,4,1,0), /* Corps de page */
              (381,122,4,4,1,4,1,0) /* Footer */
        ");

        // Creation zone G16
        $this->addSql("INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID, TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR, HAUTEUR, IS_DROPPABLE) VALUES
              (382,10,1,1,1,4,1,0), /* Navigation */
              (382,121,2,2,1,4,1,0), /* Header */
              (382,148,3,3,1,4,1,0), /* Corps de page */
              (382,122,4,4,1,4,1,0) /* Footer */
        ");

        // Mise à jour le tableau récapitulatif des gabarits
        // G15_Promo APV – Store locator
        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
          (4512, 'NDP_PT21_NAVIGATION', 381, 10, 798, 1, NULL, NULL, NULL, 30), /* Area: Navigation */
          (4513, 'NDP_PN7_ENTETE', 381, 121, 791, 2, NULL, NULL, NULL, 30), /* Area: Header */
          (4514, 'NDP_PT22_MY_PEUGEOT', 381, 121, 826, 3, NULL, NULL, NULL, 30), /* Area: Header */
          (4515, 'NDP_PT3_JE_VEUX', 381, 121, 801, 4, NULL, NULL, NULL, 30), /* Area: Header */
          (4516, 'NDP_PF11_RECHERCHE_POINT_DE_VENTE', 381, 148, 812, 5, NULL, NULL, NULL, 30), /* Area: Corps de page */
          (4517, 'NDP_PT2_FOOTER', 381, 122, 800, 6, NULL, NULL, NULL, 30) /* Area: Footer */
          ");

        // G16_Dealer locator – Devenir Agent
        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
          (4518, 'NDP_PT21_NAVIGATION', 382, 10, 798, 1, NULL, NULL, NULL, 30), /* Area: Navigation */
          (4519, 'NDP_PN7_ENTETE', 382, 121, 791, 2, NULL, NULL, NULL, 30), /* Area: Header */
          (4520, 'NDP_PT22_MY_PEUGEOT', 382, 121, 826, 3, NULL, NULL, NULL, 30), /* Area: Header */
          (4521, 'NDP_PT3_JE_VEUX', 382, 121, 801, 4, NULL, NULL, NULL, 30), /* Area: Header */
          (4522, 'NDP_PF44_DEVENIR_AGENT', 382, 148, 763, 5, NULL, NULL, NULL, 30), /* Area: Corps de page */
          (4523, 'NDP_PT2_FOOTER', 382, 122, 800, 6, NULL, NULL, NULL, 30) /* Area: Footer */
          ");

        // Traduction
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_G15_PROMO_APV_STORE_LOCATOR', null, 2, null, null, 1, null),
              ('NDP_G16_DEALER_LOCATOR_DEVENIR_AGENT', null, 2, null, null, 1, null);");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_G15_PROMO_APV_STORE_LOCATOR', 1, 1, 'G15_Promo APV – Store locator'),
              ('NDP_G16_DEALER_LOCATOR_DEVENIR_AGENT', 1, 1, 'G16_Dealer locator – Devenir Agent');");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
          (4503, 'NDP_PF44_DEVENIR_AGENT', 290, 150, 763, 36, NULL, NULL, NULL, 30),
          (4373, 'NDP - PF11 Devenir agent', 364, 150, 763, 5, 5, NULL, NULL, 30)");

        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE="Dealer Locator" WHERE LABEL_ID = "NDP_TP_DEALER_LOCATOR"');

        $this->addSql("DELETE FROM psa_zone_template WHERE ZONE_TEMPLATE_ID IN (4512,4513,4514,4515,4516,4517,4518,4519,4520,4521,4522,4523)");

        $this->addSql("DELETE FROM psa_template_page_area WHERE TEMPLATE_PAGE_ID IN (381, 382)");

        $this->addSql('DELETE FROM psa_template_page WHERE TEMPLATE_PAGE_ID IN (381, 382)');

        $this->addSql("DELETE FROM psa_page_type WHERE PAGE_TYPE_ID in (40,50)");

        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_G15_PROMO_APV_STORE_LOCATOR",
                    "NDP_G16_DEALER_LOCATOR_DEVENIR_AGENT"
                )'
            );
        }
    }
}
