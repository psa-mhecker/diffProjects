<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401191514 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_page_zone_multi_cta_cta (PAGE_ZONE_CTA_CTA_ID INT NOT NULL, PAGE_ZONE_CTA_ID INT NOT NULL, PAGE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, PAGE_VERSION INT NOT NULL, ZONE_TEMPLATE_ID INT NOT NULL, PAGE_ZONE_MULTI_ID INT NOT NULL, PAGE_ZONE_MULTI_TYPE VARCHAR(100) NOT NULL, PAGE_ZONE_MULTI_ORDER INT DEFAULT NULL, PAGE_ZONE_CTA_STATUS INT DEFAULT NULL, PAGE_ZONE_CTA_TYPE VARCHAR(50) NOT NULL, PAGE_ZONE_CTA_ORDER INT DEFAULT NULL, PAGE_ZONE_CTA_LABEL VARCHAR(50) DEFAULT NULL, DESCRIPTION LONGTEXT DEFAULT NULL, TARGET VARCHAR(50) NOT NULL, STYLE VARCHAR(50) NOT NULL, CTA_REF_TYPE VARCHAR(50) NOT NULL, CTA_ID INT NOT NULL, INDEX IDX_6000D602B4EDB1E5622E2C229381310F15EAE15F55F4D23FD9DFAC6 (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE), INDEX IDX_6000D602E1DF977A (CTA_ID), INDEX IDX_6000D602B4EDB1E5622E2C2 (PAGE_ID, LANGUE_ID), PRIMARY KEY(PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta ADD CONSTRAINT FK_6000D602B4EDB1E5622E2C229381310F15EAE15F55F4D23FD9DFAC6 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE) REFERENCES psa_page_zone_multi (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta ADD CONSTRAINT FK_6000D602E1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta ADD CONSTRAINT FK_6000D602B4EDB1E5622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID) REFERENCES psa_page (PAGE_ID, LANGUE_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE psa_page_zone_multi_cta_cta');
    }
}
