<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150422110752 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // MAJ gabarit blanc
        $this->addSql("UPDATE  psa_template_page_area SET  TEMPLATE_PAGE_AREA_ORDER =  '2', LIGNE =  '2' WHERE  TEMPLATE_PAGE_ID =290 AND  AREA_ID =150");
        $this->addSql("INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID, TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR, HAUTEUR, IS_DROPPABLE) VALUES
                ('290', '121', '1', '1', '1', '4', '1', '0'),
                ('290', '122', '3', '3', '1', '4', '1', '0')
            ");

        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '4' WHERE ZONE_TEMPLATE_ID =4109");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '5' WHERE ZONE_TEMPLATE_ID =4110");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '6' WHERE ZONE_TEMPLATE_ID =4111");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '7' WHERE ZONE_TEMPLATE_ID =4113");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '8' WHERE ZONE_TEMPLATE_ID =4128");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '9' WHERE ZONE_TEMPLATE_ID =4147");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '10' WHERE ZONE_TEMPLATE_ID =4148");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '11' WHERE ZONE_TEMPLATE_ID =4150");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '12' WHERE ZONE_TEMPLATE_ID =4151");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '13' WHERE ZONE_TEMPLATE_ID =4152");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '14' WHERE ZONE_TEMPLATE_ID =4154");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '15' WHERE ZONE_TEMPLATE_ID =4199");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '16' WHERE ZONE_TEMPLATE_ID =4200");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '17' WHERE ZONE_TEMPLATE_ID =4202");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '18' WHERE ZONE_TEMPLATE_ID =4204");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '19' WHERE ZONE_TEMPLATE_ID =4205");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '20' WHERE ZONE_TEMPLATE_ID =4206");
        $this->addSql("UPDATE  psa_zone_template SET  ZONE_TEMPLATE_ORDER =  '21' WHERE ZONE_TEMPLATE_ID =4207");


        $this->addSql("INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
            ('4000', 'NDP_PT21_NAVIGATION', '290', '121', '798', '1', NULL, NULL, NULL, '0'),
            ('4001', 'NDP_PN7_ENTETE', '290', '121', '791', '2', NULL, NULL, NULL, '0'),
            ('4002', 'NDP_PT3_JE_VEUX', '290', '121', '801', '3', NULL, NULL, NULL, '0'),
            ('4003', 'NDP_PT2_FOOTER', '290', '122', '800', '22', NULL, NULL, NULL, '0')
            ");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
