<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150611143252 extends AbstractMigration
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
        $this->addSql("INSERT INTO `psa_page_type` VALUES (32,'NDP - Car Selector','G09',1,1,NULL,'','',NULL) ");
        // ajout du gabarit
        $this->addSql("INSERT INTO `psa_template_page` VALUES (377,2,32,'NDP - Car Selector',NULL) ");
        // creation zone
        $this->addSql("INSERT INTO `psa_template_page_area` VALUES
                  (377,121,1,1,1,4,1,0),
                  (377,122,3,3,1,4,1,0),
                  (377,150,2,2,1,4,1,0)
                  ");
        // ajout des blocs dans les zones
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4416,'NDP_PT21_NAVIGATION',377,121,798,1,1,NULL,NULL,30),
              (4417,'NDP_PN7_ENTETE',377,121,791,2,2,NULL,NULL,30),
              (4418,'NPD_PT22_MY_PEUGEOT',377,121,826,3,NULL,NULL,NULL,30),
              (4419,'NPD_PT3_JE_VEUX',377,121,801,4,NULL,NULL,NULL,30),

              (4420,'NDP_PF25_FILTRES_RESULTATS_CAR_SELECTOR',377,150,813,5,NULL,NULL,NULL,30),
              (4421,'NDP_PF30_POPIN_CODE_POSTAL',377,150,827,6,NULL,NULL,NULL,30),
              (4422,'NDP_PC41_MENTIONS_JURIDIQUES',377,150,772,7,NULL,NULL,NULL,30),
              (4423,'NDP_PC33_OFFRE_PLUS',377,150,769,8,NULL,NULL,NULL,30),
              (4424,'NDP_PC40_CTA',377,150,771,9,NULL,NULL,NULL,30),

              (4425,'NPD_PT2_FOOTER',377,122,800,10,NULL,NULL,NULL,30)
              ");

        // Fix gabarit 01 home page
        // on decale les tranches apres pt21 de 2 rang pour inserer pt22 et pt3
        $this->addSql('UPDATE  psa_zone_template SET ZONE_TEMPLATE_ORDER = ZONE_TEMPLATE_ORDER + 2 WHERE TEMPLATE_PAGE_ID=363  AND ZONE_TEMPLATE_ORDER > 1');
        // on inser les 2 block pt22 et pt3
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4426,'NPD_PT22_MY_PEUGEOT',363,121,826,2,NULL,NULL,NULL,30),
              (4427,'NPD_PT3_JE_VEUX',363,121,801,3,NULL,NULL,NULL,30)
              ");
        // on decale les tranches apres pc19 de 1 rang pour inserer pf23
        $this->addSql('UPDATE  psa_zone_template SET ZONE_TEMPLATE_ORDER = ZONE_TEMPLATE_ORDER + 1 WHERE TEMPLATE_PAGE_ID=363 AND ZONE_TEMPLATE_ORDER > 4');
        // on inserer pf23
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4428,'NDP_PF23_RANGE_BAR',363,148,828,5,NULL,NULL,NULL,30)
              ");
        // on decale le footer de 7 tranches
        $this->addSql('UPDATE  psa_zone_template SET ZONE_TEMPLATE_ORDER = ZONE_TEMPLATE_ORDER + 7 WHERE ZONE_TEMPLATE_ID=4338');
        // on ajoute les tranches manquantes a la zone dynamique
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4429,'NDP_PC39_SLIDESHOW_OFFRE',363,150,756,11,NULL,NULL,NULL,30),
              (4430,'NDP_PC42_ACTUALITES',363,150,773,12,NULL,NULL,NULL,30),
              (4431,'NDP_PF11_RECHERCHE_POINT_DE_VENTE',363,150,812,13,NULL,NULL,NULL,30),
              (4432,'NDP_PF27_CAR_PICKER',363,150,782,14,NULL,NULL,NULL,30),
              (4433,'NDP_PC43_APPLICATIONS_MOBILES',363,150,774,15,NULL,NULL,NULL,30),
              (4434,'NDP_PC73_MEGA_BANNIERE_DYNAMIQUE',363,150,829,16,NULL,NULL,NULL,30),
              (4435,'NDP_PC79_LIGHT_MEDIA_WALL',363,150,816,17,NULL,NULL,NULL,30)
              ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        // supression des block inserer
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID IN (4426,4427,4428,4429,4430,4431,4432,4433,4434,4435)");

        // supression gabarit 09 car selector
        $this->addSql("DELETE FROM `psa_zone_template` WHERE TEMPLATE_PAGE_ID = 377");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE TEMPLATE_PAGE_ID = 377");
        $this->addSql("DELETE FROM `psa_template_page` WHERE  TEMPLATE_PAGE_ID = 377");
        $this->addSql("DELETE FROM `psa_page_type` WHERE PAGE_TYPE_ID =  32");
    }
}
