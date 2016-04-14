<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150716163256 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO psa_page_datalayer VALUES (1,1,'brand:peugeot\r\nvirtualPageURL:kpp/index\r\npageName:kpp/index/new cars/all/central/G01_Homepage/desktop//##pageTitle##\r\nlanguage:##language##	\r\ncountry:##country##	\r\nsiteTypeLevel1:kpp\r\nsiteTypeLevel2:index\r\nsiteOwner:central\r\nsiteTarget:all\r\nsiteFamily:new cars\r\npageCategory:home page');");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_page_datalayer");

    }
}
