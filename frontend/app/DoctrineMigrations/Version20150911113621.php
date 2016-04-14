<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150911113621 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 3, `ZONE_TEMPLATE_MOBILE_ORDER` = 3 WHERE `ZONE_TEMPLATE_ID` = 1;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 4, `ZONE_TEMPLATE_MOBILE_ORDER` = 4 WHERE `ZONE_TEMPLATE_ID` = 2;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 5, `ZONE_TEMPLATE_MOBILE_ORDER` = 5 WHERE `ZONE_TEMPLATE_ID` = 3;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 6, `ZONE_TEMPLATE_MOBILE_ORDER` = 6 WHERE `ZONE_TEMPLATE_ID` = 4;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 7, `ZONE_TEMPLATE_MOBILE_ORDER` = 7 WHERE `ZONE_TEMPLATE_ID` = 5;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 8, `ZONE_TEMPLATE_MOBILE_ORDER` = 8 WHERE `ZONE_TEMPLATE_ID` = 6;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 9, `ZONE_TEMPLATE_MOBILE_ORDER` = 9 WHERE `ZONE_TEMPLATE_ID` = 7;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 10, `ZONE_TEMPLATE_MOBILE_ORDER` = 10 WHERE `ZONE_TEMPLATE_ID` = 8;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 3, `ZONE_TEMPLATE_MOBILE_ORDER` = 3 WHERE `ZONE_TEMPLATE_ID` = 1;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 4, `ZONE_TEMPLATE_MOBILE_ORDER` = 4 WHERE `ZONE_TEMPLATE_ID` = 2;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 6, `ZONE_TEMPLATE_MOBILE_ORDER` = 6 WHERE `ZONE_TEMPLATE_ID` = 3;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 7, `ZONE_TEMPLATE_MOBILE_ORDER` = 7 WHERE `ZONE_TEMPLATE_ID` = 4;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 5, `ZONE_TEMPLATE_MOBILE_ORDER` = 5 WHERE `ZONE_TEMPLATE_ID` = 5;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 8, `ZONE_TEMPLATE_MOBILE_ORDER` = 8 WHERE `ZONE_TEMPLATE_ID` = 6;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 9, `ZONE_TEMPLATE_MOBILE_ORDER` = 9 WHERE `ZONE_TEMPLATE_ID` = 7;');
        $this->addSql('UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` = 10, `ZONE_TEMPLATE_MOBILE_ORDER` = 10 WHERE `ZONE_TEMPLATE_ID` = 8;');
    }
}
