<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150908091855 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO `psa_profile_directory` (`PROFILE_ID`, `DIRECTORY_ID`, `PROFILE_DIRECTORY_ORDER`) VALUES
                (18, 234, 18020)
        ');

        $this->addSql('INSERT INTO `psa_directory_site` (`DIRECTORY_ID`, `SITE_ID`) VALUES
                (234, 4)
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM `psa_directory_site` WHERE `DIRECTORY_ID` = 234 AND `SITE_ID` = 4');

        $this->addSql('DELETE FROM `psa_profile_directory` WHERE `PROFILE_ID` = 18 AND `DIRECTORY_ID` = 234 AND `PROFILE_DIRECTORY_ORDER` = 18020');
    }
}
