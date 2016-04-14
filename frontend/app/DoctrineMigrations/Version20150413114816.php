<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413114816 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT20_MASTER_PAGE', `ZONE_FO_PATH` = 'Pt20MasterPageStrategy' WHERE `ZONE_ID` = 811");
        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_ID` IN (796, 797)");
        $this->addSql("DELETE FROM `psa_zone` WHERE `ZONE_ID` IN (796, 797)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
