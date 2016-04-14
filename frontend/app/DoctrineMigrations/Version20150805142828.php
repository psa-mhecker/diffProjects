<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150805142828 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        //Ajout PC79 gabarit de test
        $this->addSql("INSERT INTO `psa_zone_template` VALUES
           (4700, 'NDP_PC79_LIGHT_MEDIA_WALL'                 ,290, 150, 816, 28, NULL, NULL, NULL,30)
       ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_zone_template WHERE ZONE_TEMPLATE_ID = 4700');
    }
}
