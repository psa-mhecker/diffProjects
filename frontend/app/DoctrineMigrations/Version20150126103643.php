<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150126103643 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_content_version_cta (ID INT AUTO_INCREMENT NOT NULL, DESCRIPTION LONGTEXT DEFAULT NULL, CONTENT_ID INT DEFAULT NULL, LANGUE_ID INT DEFAULT NULL, PAGE_VERSION INT DEFAULT NULL, CTA_ID INT NOT NULL, PAGE_ID INT DEFAULT NULL, INDEX IDX_A5107385B772F8325622E2C229381310 (CONTENT_ID, LANGUE_ID, PAGE_VERSION), INDEX IDX_A5107385E1DF977A (CTA_ID), INDEX IDX_A5107385B4EDB1E5622E2C2 (PAGE_ID, LANGUE_ID), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD CONSTRAINT FK_A5107385B772F8325622E2C229381310 FOREIGN KEY (CONTENT_ID, LANGUE_ID, PAGE_VERSION) REFERENCES psa_content_version (CONTENT_ID, LANGUE_ID, CONTENT_VERSION)');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD CONSTRAINT FK_A5107385E1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD CONSTRAINT FK_A5107385B4EDB1E5622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID) REFERENCES psa_page (PAGE_ID, LANGUE_ID)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE psa_content_version_cta');
    }
}
