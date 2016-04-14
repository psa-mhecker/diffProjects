<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150619092930 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // ajout de pc18 dans gabarit 09
        $this->addSql("INSERT INTO `psa_zone_template` VALUES  (4446,'NDP_PC18_CONTENU_GRAND_VISUEL',377,150,753,8,NULL,NULL,NULL,30) ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // ajout de pc18 dans gabarit 09
        $this->addSql('DELETE FROM  `psa_zone_template` WHERE ZONE_TEMPLATE_ID = 4446');

    }
}
