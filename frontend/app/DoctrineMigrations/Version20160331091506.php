<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160331091506 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
       $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID=234');
       $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID=234');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (234, 2)');
        $this->addSql('INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (234, 14)');
        $this->addSql('INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (234, 15)');
        $this->addSql('INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (234, 16)');
        $this->addSql('INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (234, 17)');

        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (2, 234, 2023)');
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (68, 234, 68024)');
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (73, 234, 73024)');
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (78, 234, 78024)');
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (83, 234, 83024)');
    }
}
