<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150727141727 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE psa_services_connect_finition_grouping (LCDV4 VARCHAR(4) NOT NULL, OPTIONS INT, FINITION_GROUPING_ID VARCHAR(255) NOT NULL, CONNECT_FINITION_ID INT DEFAULT NULL, CONNECTED_SERVICE_ID INT DEFAULT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_CEC4E7903CDDF5D8 (CONNECT_FINITION_ID), INDEX IDX_CEC4E790D05290C8 (CONNECTED_SERVICE_ID), INDEX IDX_CEC4E7905622E2C2 (LANGUE_ID), INDEX IDX_CEC4E790F1B5AEBC (SITE_ID), PRIMARY KEY(LCDV4, FINITION_GROUPING_ID, CONNECTED_SERVICE_ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E7903CDDF5D8 FOREIGN KEY (CONNECT_FINITION_ID) REFERENCES psa_services_connect_finition (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E790D05290C8 FOREIGN KEY (CONNECTED_SERVICE_ID) REFERENCES psa_services_connect (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E7905622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_services_connect_finition_grouping ADD CONSTRAINT FK_CEC4E790F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID) ON DELETE CASCADE');
       
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE psa_services_connect_finition_grouping');
    }
}
