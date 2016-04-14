<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160223130626 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // only master site users can access diffusion
        // clean acl
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID=187 AND PROFILE_ID IN (select PROFILE_ID FROM psa_profile WHERE SITE_ID > 2)');
        // clean directory INPUT
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID =187 AND SITE_ID >2');
        $this->addSql('REPLACE INTO psa_profile_directory VALUES(3,187,3024)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_profile_directory WHERE PROFILE_ID=3 AND DIRECTORY_ID=187');
    }
}
