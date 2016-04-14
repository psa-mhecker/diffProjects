<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150608111723 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // suppression au cas ou deja existant sur certains environnements
        $this->addSql("DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 182 AND SITE_ID = 1");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (182, 1)");

        $this->addSql("DELETE FROM psa_profile_directory WHERE PROFILE_ID = 1");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (1, 1, 1001),
            (1, 2, 1015),
            (1, 4, 1017),
            (1, 5, 1018),
            (1, 7, 1002),
            (1, 8, 1003),
            (1, 9, 1004),
            (1, 30, 1019),
            (1, 31, 1016),
            (1, 32, 1023),
            (1, 34, 1025),
            (1, 39, 1010),
            (1, 40, 1011),
            (1, 41, 1012),
            (1, 49, 1024),
            (1, 54, 1005),
            (1, 55, 1006),
            (1, 56, 1009),
            (1, 57, 1007),
            (1, 66, 1014),
            (1, 76, 1026),
            (1, 140, 1013),
            (1, 178, 1008),
            (1, 179, 1020),
            (1, 182, 1021),
            (1, 212, 1022)
        ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
