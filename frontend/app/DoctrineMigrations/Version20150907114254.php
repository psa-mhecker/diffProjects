<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150907114254 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP FOREIGN KEY FK_CEC4E7903CDDF5D8');

        $this->addSql('ALTER TABLE psa_services_connect_finition MODIFY id INT NOT NULL;');
        $this->addSql('ALTER TABLE psa_services_connect_finition DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_services_connect_finition CHANGE LANGUE_ID LANGUE_ID INT DEFAULT NULL, CHANGE SITE_ID SITE_ID INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_services_connect_finition MODIFY id INT NOT NULL PRIMARY KEY AUTO_INCREMENT;');


        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP FOREIGN KEY FK_CEC4E790D05290C8');
        $this->addSql('ALTER TABLE psa_services_connect MODIFY id INT NOT NULL;');
        $this->addSql('ALTER TABLE psa_services_connect DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_services_connect CHANGE A_PARTIR_DE A_PARTIR_DE VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE psa_services_connect ADD CONSTRAINT FK_EE0C2F7A5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_services_connect ADD CONSTRAINT FK_EE0C2F7AF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('CREATE INDEX IDX_EE0C2F7A5622E2C2 ON psa_services_connect (LANGUE_ID)');
        $this->addSql('CREATE INDEX IDX_EE0C2F7AF1B5AEBC ON psa_services_connect (SITE_ID)');
        $this->addSql('ALTER TABLE psa_services_connect MODIFY id INT NOT NULL PRIMARY KEY AUTO_INCREMENT;');

        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP FOREIGN KEY FK_CEC4E7905622E2C2');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP FOREIGN KEY FK_CEC4E790F1B5AEBC');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping CHANGE CONNECT_FINITION_ID CONNECT_FINITION_ID INT NOT NULL, CHANGE CONNECTED_SERVICE_ID CONNECTED_SERVICE_ID INT NOT NULL');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E7903CDDF5D8 FOREIGN KEY (CONNECT_FINITION_ID) REFERENCES psa_services_connect_finition (ID)');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E7905622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E790D05290C8 FOREIGN KEY (CONNECTED_SERVICE_ID) REFERENCES psa_services_connect (ID)');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E790F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD PRIMARY KEY (LCDV4, CONNECT_FINITION_ID, CONNECTED_SERVICE_ID, FINITION_GROUPING_ID, LANGUE_ID, SITE_ID)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE psa_services_connect DROP FOREIGN KEY FK_EE0C2F7A5622E2C2');
        $this->addSql('ALTER TABLE psa_services_connect DROP FOREIGN KEY FK_EE0C2F7AF1B5AEBC');
        $this->addSql('DROP INDEX IDX_EE0C2F7A5622E2C2 ON psa_services_connect');
        $this->addSql('DROP INDEX IDX_EE0C2F7AF1B5AEBC ON psa_services_connect');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP FOREIGN KEY FK_CEC4E790D05290C8');
        $this->addSql('ALTER TABLE psa_services_connect MODIFY id INT NOT NULL;');
        $this->addSql('ALTER TABLE psa_services_connect DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_services_connect CHANGE ID ID INT NOT NULL, CHANGE A_PARTIR_DE A_PARTIR_DE VARCHAR(11) NOT NULL COLLATE latin1_swedish_ci, CHANGE LANGUE_ID LANGUE_ID INT NOT NULL, CHANGE SITE_ID SITE_ID INT NOT NULL');
        $this->addSql('ALTER TABLE psa_services_connect ADD PRIMARY KEY (ID, LANGUE_ID, SITE_ID)');
        $this->addSql('ALTER TABLE psa_services_connect MODIFY id INT NOT NULL AUTO_INCREMENT;');

        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP FOREIGN KEY FK_CEC4E7903CDDF5D8');
        $this->addSql('ALTER TABLE psa_services_connect_finition MODIFY id INT NOT NULL;');
        $this->addSql('ALTER TABLE psa_services_connect_finition DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP FOREIGN KEY FK_CEC4E7905622E2C2');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP FOREIGN KEY FK_CEC4E790F1B5AEBC');
        $this->addSql('ALTER TABLE psa_services_connect_finition CHANGE ID ID INT NOT NULL');
        $this->addSql('ALTER TABLE psa_services_connect_finition DROP FOREIGN KEY FK_A09594715622E2C2');
        $this->addSql('ALTER TABLE psa_services_connect_finition DROP FOREIGN KEY FK_A0959471F1B5AEBC');

        $this->addSql('ALTER TABLE psa_services_connect_finition ADD PRIMARY KEY (ID, LANGUE_ID, SITE_ID)');
        $this->addSql('ALTER TABLE psa_services_connect_finition MODIFY id INT NOT NULL AUTO_INCREMENT;');

        $this->addSql('ALTER TABLE psa_services_connect_finition ADD CONSTRAINT FK_A09594715622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_services_connect_finition ADD CONSTRAINT FK_A0959471F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');


        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping CHANGE CONNECT_FINITION_ID CONNECT_FINITION_ID INT DEFAULT NULL, CHANGE CONNECTED_SERVICE_ID CONNECTED_SERVICE_ID INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E7903CDDF5D8 FOREIGN KEY (CONNECT_FINITION_ID) REFERENCES psa_services_connect_finition (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E790D05290C8 FOREIGN KEY (CONNECTED_SERVICE_ID) REFERENCES psa_services_connect (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E7905622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E790F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD PRIMARY KEY (LCDV4, FINITION_GROUPING_ID, CONNECTED_SERVICE_ID, LANGUE_ID, SITE_ID)');


    }
}
