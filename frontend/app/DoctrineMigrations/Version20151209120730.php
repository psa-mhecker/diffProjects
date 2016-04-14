<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151209120730 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        // ajout de la pc23 dans le zone dymanique du template navlight
        $this->addSql("INSERT INTO `psa_zone_template` VALUES (6078,'NDP_PC23_MUR_MEDIA',1015,150,802,18,18, NULL,NULL,-2)");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID=6078 ");
    }
}
