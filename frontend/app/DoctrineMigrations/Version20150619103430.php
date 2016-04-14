<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150619103430 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // update gabarit 02
        // changement de la positon de pn7
        $this->addSql('UPDATE `psa_zone_template` SET ZONE_TEMPLATE_ORDER = 2 WHERE ZONE_TEMPLATE_ID = 4343 ');
        // ajout des tranches pt22 et pt3
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4447,'NDP_PT22_MY_PEUGEOT',366,121,826,3,NULL,NULL,NULL,30),
              (4448,'NDP_PT3_JE_VEUX',366,121,801,4,NULL,NULL,NULL,30)
              ");

        // update gabarit 06
        // changement de la positon de pn7
        $this->addSql('UPDATE `psa_zone_template` SET ZONE_TEMPLATE_ORDER = 2 WHERE ZONE_TEMPLATE_ID = 4361 ');
        // ajout des tranches pt22 et pt3
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4449,'NDP_PT22_MY_PEUGEOT',365,121,826,3,NULL,NULL,NULL,30),
              (4450,'NDP_PT3_JE_VEUX',365,121,801,4,NULL,NULL,NULL,30)
              ");

        // update gabarit 04 404
        // changement de la positon de pn7
        $this->addSql('UPDATE `psa_zone_template` SET ZONE_TEMPLATE_ORDER = 2 WHERE ZONE_TEMPLATE_ID = 4356 ');
        // ajout des tranches pt22 et pt3
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4451,'NDP_PT22_MY_PEUGEOT',362,121,826,3,NULL,NULL,NULL,30),
              (4452,'NDP_PT3_JE_VEUX',362,121,801,4,NULL,NULL,NULL,30)
              ");

        // update gabarit 08 plan du site
        // changement de la positon de pn7
        $this->addSql('UPDATE `psa_zone_template` SET ZONE_TEMPLATE_ORDER = 2 WHERE ZONE_TEMPLATE_ID = 4366 ');
        // ajout des tranches pt22 et pt3
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4453,'NDP_PT22_MY_PEUGEOT',367,121,826,3,NULL,NULL,NULL,30),
              (4454,'NDP_PT3_JE_VEUX',367,121,801,4,NULL,NULL,NULL,30)
              ");

        // update gabarit 10 dealer locator
        // changement de la positon de pn7
        $this->addSql('UPDATE `psa_zone_template` SET ZONE_TEMPLATE_ORDER = 2 WHERE ZONE_TEMPLATE_ID = 4370 ');
        // ajout des tranches pt22 et pt3
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (4455,'NDP_PT22_MY_PEUGEOT',364,121,826,3,NULL,NULL,NULL,30),
              (4456,'NDP_PT3_JE_VEUX',364,121,801,4,NULL,NULL,NULL,30)
              ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // suppression des tranches du gabarit
        $this->addSql('DELETE FROM  `psa_zone_template` WHERE ZONE_TEMPLATE_ID IN (4447,4448,4449,4450,4451,4452,4453,4454,4455,4456)');
    }
}
