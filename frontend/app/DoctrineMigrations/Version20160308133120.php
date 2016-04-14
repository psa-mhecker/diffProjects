<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160308133120 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID IN (83,84,81,121,122,268)');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID IN (83,84,81,121,122,268)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 83, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 84, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 81, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 121, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 122, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 268, SITE_ID FROM psa_site');

    }
}
