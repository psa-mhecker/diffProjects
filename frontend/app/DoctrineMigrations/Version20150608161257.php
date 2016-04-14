<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150608161257 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE psa_profile_directory SET PROFILE_DIRECTORY_ORDER = 2074 WHERE DIRECTORY_ID = 119');
        $this->addSql('UPDATE psa_profile_directory SET PROFILE_DIRECTORY_ORDER = 2075 WHERE DIRECTORY_ID = 120');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE psa_profile_directory SET PROFILE_DIRECTORY_ORDER = 2068 WHERE DIRECTORY_ID = 119');
        $this->addSql('UPDATE psa_profile_directory SET PROFILE_DIRECTORY_ORDER = 2070 WHERE DIRECTORY_ID = 120');
    }
}
