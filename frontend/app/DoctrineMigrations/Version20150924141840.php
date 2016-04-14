<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150924141840 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Ndp_Administration_Url' WHERE `TEMPLATE_ID` = '296';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Citroen_Administration_Url' WHERE `TEMPLATE_ID` = '296';");
    }
}
