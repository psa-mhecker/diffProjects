<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150123165617 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_page_zone_multi_cta (ID INT AUTO_INCREMENT NOT NULL, DESCRIPTION LONGTEXT DEFAULT NULL, PAGE_ID INT DEFAULT NULL, LANGUE_ID INT DEFAULT NULL, PAGE_VERSION INT DEFAULT NULL, ZONE_TEMPLATE_ID INT DEFAULT NULL, PAGE_ZONE_MULTI_ID INT DEFAULT NULL, PAGE_ZONE_MULTI_TYPE VARCHAR(100) DEFAULT NULL, CTA_ID INT NOT NULL, INDEX IDX_59FECE2CB4EDB1E5622E2C229381310F15EAE15F55F4D23FD9DFAC6 (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE), INDEX IDX_59FECE2CE1DF977A (CTA_ID), INDEX IDX_59FECE2CB4EDB1E5622E2C2 (PAGE_ID, LANGUE_ID), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta ADD CONSTRAINT FK_59FECE2CB4EDB1E5622E2C229381310F15EAE15F55F4D23FD9DFAC6 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE) REFERENCES psa_page_zone_multi (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta ADD CONSTRAINT FK_59FECE2CE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta ADD CONSTRAINT FK_59FECE2CB4EDB1E5622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID) REFERENCES psa_page (PAGE_ID, LANGUE_ID)');

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE psa_page_zone_multi_cta');
    }
}
