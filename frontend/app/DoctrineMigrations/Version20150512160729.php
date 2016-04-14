<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150512160729 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->down($schema);
        $this->addSql("INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES('CONFIGURATION_WEBSERVICES', NULL, 2, NULL, 0, 1, NULL);");
        $this->addSql("INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES('CONFIGURATION_WEBSERVICES', 1, 'Configuration des WS', '');");
        $this->addSql("insert into psa_directory values	(223,322,4,0,'&id=1',NULL,'Configuration Webservices',NULL,NULL);");
        $this->addSql("insert into psa_directory_site values	(223,2);");
        $this->addSql("insert into psa_profile_directory values(2,223,31030);");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID ='CONFIGURATION_WEBSERVICES'");
        $this->addSql("DELETE FROM psa_label_langue WHERE LABEL_ID ='CONFIGURATION_WEBSERVICES'");
        $this->addSql("DELETE FROM psa_profile_directory WHERE DIRECTORY_ID =223");
        $this->addSql("DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 223");
        $this->addSql("DELETE FROM psa_directory WHERE DIRECTORY_ID =223");

    }
}
