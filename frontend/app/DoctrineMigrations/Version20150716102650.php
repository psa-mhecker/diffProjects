<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150716102650 extends AbstractMigration
{
    public function preUp(Schema $schema)
    {
        $this->addSql('ALTER TABLE  psa_page_datalayer DROP PRIMARY KEY , ADD PRIMARY KEY (  PAGE_ID ,  LANGUE_ID )');

    }

    public function preDown(Schema $schema)
    {
       $this->addSql('ALTER TABLE  psa_page_datalayer ADD  SITE_ID INT( 11 ) NOT NULL FIRST');
    }
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql('ALTER TABLE psa_page_datalayer DROP COLUMN SITE_ID');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
      $this->addSql('ALTER TABLE  psa_page_datalayer DROP PRIMARY KEY , ADD PRIMARY KEY (  PAGE_ID ,  LANGUE_ID ,  SITE_ID )');

    }
}

