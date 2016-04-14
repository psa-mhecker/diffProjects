<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150928150220 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
          UPDATE `psa_language` SET `LANGUE_CODE` = 'ar' WHERE `LANGUE_ID` = 5;
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("
          UPDATE `psa_language` SET `LANGUE_CODE` = 'ae' WHERE `LANGUE_ID` = 5;
        ");
    }
}
