<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150724163307 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE psa_services_connect_finition (ID INT AUTO_INCREMENT NOT NULL, MODELE VARCHAR(45) NOT NULL, COMPATIBILITE TINYINT(1) NOT NULL, SERVICES_CONNECTES VARCHAR(255) DEFAULT NULL, MENTIONS_LEGALES VARCHAR(255) DEFAULT NULL, CTA_SERVICE INT NOT NULL, CTA_SERVICE_ID INT DEFAULT NULL, CTA_SERVICE_ACTION VARCHAR(255) DEFAULT NULL, CTA_SERVICE_TITLE VARCHAR(255) DEFAULT NULL, CTA_SERVICE_TARGET VARCHAR(255) DEFAULT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_A09594715622E2C2 (LANGUE_ID), INDEX IDX_A0959471F1B5AEBC (SITE_ID), PRIMARY KEY(ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_services_connect_finition ADD CONSTRAINT FK_A09594715622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_services_connect_finition ADD CONSTRAINT FK_A0959471F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
       
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE psa_services_connect_finition');
    }
}
