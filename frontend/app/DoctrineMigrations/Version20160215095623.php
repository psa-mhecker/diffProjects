<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160215095623 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 150 WHERE `ZONE_TEMPLATE_ID`= 6081");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = 148 WHERE `ZONE_TEMPLATE_ID`= 6081");

    }
}
