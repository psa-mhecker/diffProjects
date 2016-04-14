<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151005105844 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        
        $uniqueExist = $this->connection->fetchAll("   
            SELECT COUNT(1) as val FROM INFORMATION_SCHEMA.STATISTICS
            WHERE table_schema = 'psa-ndp'
            AND table_name = 'psa_model_site'
            AND index_name = 'UNIQ_F5DB39BF68469EFD'");
        if ($uniqueExist[0]['val'] == 1) {
            $this->addSql('TRUNCATE TABLE psa_model_site');
            $this->addSql('ALTER TABLE psa_model_site DROP INDEX UNIQ_F5DB39BF68469EFD');
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
