<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150827152232 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(' DELETE FROM `psa_profile_directory`  WHERE  `DIRECTORY_ID` = 223 AND `PROFILE_ID` <> 2 ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('INSERT INTO `psa_profile_directory` (`PROFILE_ID`, `DIRECTORY_ID`, `PROFILE_DIRECTORY_ORDER`) VALUES
                (13, 223, 13073),
                (18, 223, 18075)
        ');
    }
}
