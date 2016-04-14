<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160308133130 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID IN (117,250,202)');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID IN (117,250, 202)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 117, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 250, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 202, SITE_ID FROM psa_site');


    }
}
