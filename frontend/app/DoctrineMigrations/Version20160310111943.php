<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160310111943 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 96 AND SITE_ID != 2');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 96 AND PROFILE_ID != 2');

        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 113');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 113');

        $this->addSql('UPDATE psa_directory SET DIRECTORY_PARENT_ID = 96 WHERE DIRECTORY_ID = 101');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 100');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 100');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 113, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 100, SITE_ID FROM psa_site');
        $this->addSql('UPDATE psa_directory SET DIRECTORY_PARENT_ID = 100 WHERE DIRECTORY_ID = 101');
    }
}
