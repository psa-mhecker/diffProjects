<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150911102803 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = \'2\', `ZONE_TEMPLATE_MOBILE_ORDER` = \'2\' WHERE `ZONE_TEMPLATE_ID` = 9;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = \'1\', `ZONE_TEMPLATE_MOBILE_ORDER` = \'1\' WHERE `ZONE_TEMPLATE_ID` = 20;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = \'1\', `ZONE_TEMPLATE_MOBILE_ORDER` = \'1\' WHERE `ZONE_TEMPLATE_ID` = 9;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = \'2\', `ZONE_TEMPLATE_MOBILE_ORDER` = \'2\' WHERE `ZONE_TEMPLATE_ID` = 20;');
    }
}
