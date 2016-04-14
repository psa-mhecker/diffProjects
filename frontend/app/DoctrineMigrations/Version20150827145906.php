<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150827145906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE `psa_profile_directory` SET  `PROFILE_DIRECTORY_ORDER` =  "2021" WHERE  `psa_profile_directory`.`PROFILE_ID` = 2 AND `psa_profile_directory`.`DIRECTORY_ID` = 192 ');
        $this->addSql('UPDATE `psa_profile_directory` SET  `PROFILE_DIRECTORY_ORDER` =  "2022" WHERE  `psa_profile_directory`.`PROFILE_ID` = 2 AND `psa_profile_directory`.`DIRECTORY_ID` = 202 ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE `psa_profile_directory` SET  `PROFILE_DIRECTORY_ORDER` =  "2022" WHERE  `psa_profile_directory`.`PROFILE_ID` = 2 AND `psa_profile_directory`.`DIRECTORY_ID` = 192 ');
        $this->addSql('UPDATE `psa_profile_directory` SET  `PROFILE_DIRECTORY_ORDER` =  "2021" WHERE  `psa_profile_directory`.`PROFILE_ID` = 2 AND `psa_profile_directory`.`DIRECTORY_ID` = 202 ');
    }
}
