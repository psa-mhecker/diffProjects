<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150827170758 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $tables = array('psa_directory_site', 'psa_profile_directory');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `DIRECTORY_ID` = 198');
        }

        $this->addSql('SET foreign_key_checks = 0');
        $this->addSql('DELETE FROM `psa_directory`  WHERE  `DIRECTORY_ID` = 198');
        $this->addSql('SET foreign_key_checks = 1');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('INSERT INTO `psa_directory` (`DIRECTORY_ID`, `TEMPLATE_ID`, `DIRECTORY_PARENT_ID`, `DIRECTORY_ADMIN`, `TEMPLATE_COMPLEMENT`, `DIRECTORY_LEFT_LABEL`, `DIRECTORY_LABEL`, `DIRECTORY_ICON`, `DIRECTORY_DEFAULT`) VALUES
                (198, NULL, 1, 0, NULL, NULL, \'Vehicules\', NULL, NULL)
        ');

        $this->addSql('INSERT INTO `psa_profile_directory` (`PROFILE_ID`, `DIRECTORY_ID`, `PROFILE_DIRECTORY_ORDER`) VALUES
                (2, 198, 2037),
                (13, 198, 13037),
                (18, 198, 18037)
        ');

        $this->addSql('INSERT INTO `psa_directory_site` (`DIRECTORY_ID`, `SITE_ID`) VALUES
                (198, 2),
                (198, 3),
                (198, 4)
        ');
    }
}
