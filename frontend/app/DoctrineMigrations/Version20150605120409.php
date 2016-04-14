<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150605120409 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
       $tablesWithSiteId = $this->connection->fetchAll("SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'psa-ndp' AND COLUMN_NAME = 'SITE_ID'");
       foreach ($tablesWithSiteId as $key => $table) {
           $this->addSql('DELETE t
            FROM '.$table['TABLE_NAME'].' t
            LEFT JOIN psa_site s ON s.SITE_ID = t.SITE_ID
            WHERE s.SITE_ID IS NULL;
            ');
       }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
