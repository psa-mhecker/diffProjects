<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150825145334 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
       $this->addSql("UPDATE `psa_zone` SET `ZONE_FO_PATH` = 'Pf25FilterCarSelectorStrategy' WHERE `ZONE_ID` = 813");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_FO_PATH` = '' WHERE `ZONE_ID` = 813");

    }
}
