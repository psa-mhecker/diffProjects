<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150216121334 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_site_service (SITE_SERVICE_ID INT AUTO_INCREMENT NOT NULL, SERVICE_CODE VARCHAR(30) NOT NULL, CLIENT_ID VARCHAR(255) DEFAULT NULL, CONSUMER_KEY VARCHAR(255) DEFAULT NULL, CONSUMER_SECRET VARCHAR(255) DEFAULT NULL, CONSUMER_OAUTH_TOKEN VARCHAR(255) DEFAULT NULL, CONSUMER_OAUTH_TOKEN_SECRET VARCHAR(255) DEFAULT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT DEFAULT NULL, INDEX IDX_7A387AF4F1B5AEBC (SITE_ID), UNIQUE INDEX UNIQ_7A387AF45622E2C2 (LANGUE_ID), PRIMARY KEY(SITE_SERVICE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_site_service ADD CONSTRAINT FK_7A387AF4F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_site_service ADD CONSTRAINT FK_7A387AF45622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE psa_site_service');
    }
}
