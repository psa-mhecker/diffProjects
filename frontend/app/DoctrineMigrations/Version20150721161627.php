<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150721161627 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            "UPDATE `psa_zone` SET `ZONE_TYPE_ID` = 2 WHERE `ZONE_ID` = 772"
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql(
            "UPDATE `psa_zone` SET `ZONE_TYPE_ID` = 1 WHERE `ZONE_ID` = 772"
        );
    }
}
