<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150831170002 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE  `psa_carselectorfilter` CHANGE  `WIDTH_GAUGE`  `WIDTH_GAUGE` FLOAT NULL DEFAULT NULL");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE  `psa_carselectorfilter` CHANGE  `WIDTH_GAUGE`  `WIDTH_GAUGE` FLOAT NOT NULL");

    }
}
