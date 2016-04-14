<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160324143249 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("
            REPLACE INTO `psa_directory_site` (`DIRECTORY_ID`, `SITE_ID`) VALUES
            (268, 2),
            (268, 14),
            (268, 15),
            (268, 16),
            (268, 17),
            (269, 2),
            (269, 14),
            (269, 15),
            (269, 16),
            (269, 17),
            (270, 2),
            (270, 14),
            (270, 15),
            (270, 16),
            (270, 17)
        ");

        $this->addSql("
            REPLACE INTO `psa_profile_directory` (`PROFILE_ID`, `DIRECTORY_ID`, `PROFILE_DIRECTORY_ORDER`) VALUES
            (2, 268, 2030),
            (2, 269, 2032),
            (2, 270, 2031),
            (3, 269, NULL),
            (3, 270, NULL),
            (68, 269, 68046),
            (68, 270, 68045),
            (72, 269, 72102),
            (72, 270, 72101),
            (73, 269, 73046),
            (73, 270, 73045),
            (77, 269, 77100),
            (77, 270, 77102),
            (78, 269, 78046),
            (78, 270, 78045),
            (82, 269, 82101),
            (82, 270, 82100),
            (83, 269, 83046),
            (83, 270, 83045),
            (87, 269, 87102),
            (87, 270, 87101)
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_directory_site` WHERE `DIRECTORY_ID` IN (268, 269, 270)");
        $this->addSql("DELETE FROM `psa_profile_directory` WHERE `DIRECTORY_ID` IN (268, 269, 270)");
    }
}
