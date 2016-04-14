<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160216163035 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //AGILE - NDP_TP_TECHNO
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_ID` = 822 WHERE `ZONE_TEMPLATE_ID`= 6250");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

    }
}
