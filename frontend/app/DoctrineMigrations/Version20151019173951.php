<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151019173951 extends AbstractMigration
{
    public function preUp(Schema $schema){
        $this->addSql("ALTER TABLE  psa_page_datalayer DROP PRIMARY KEY , ADD PRIMARY KEY (  PAGE_ID )");
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE psa_page_datalayer  DROP LANGUE_ID;");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE  psa_page_datalayer ADD  LANGUE_ID INT( 11 ) NOT NULL;");

    }

    public function postDown(Schema $schema)
    {
        $this->addSql("ALTER TABLE  psa_page_datalayer DROP PRIMARY KEY ,ADD PRIMARY KEY (  PAGE_ID ,  LANGUE_ID )");
    }
}
