<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150702084830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // ignore if exit PC39 in Gabarit blanc
        $this->addSql("INSERT IGNORE INTO `psa_zone_template` VALUES
              (4147,'NDP_PC39_SLIDESHOW_OFFRE',290,150,756,12,NULL,NULL,NULL,30) ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {


    }
}
