<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150914140319 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // ajout des tranches pt22 et pt3
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
              (100,'NDP_PT22_MY_PEUGEOT',290,121,826,3,NULL,NULL,NULL,30)");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // ajout des tranches pt22 et pt3
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID = 100) ");

    }
}
