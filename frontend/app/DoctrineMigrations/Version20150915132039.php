<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150915132039 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_media_alt_translation (TITLE VARCHAR(255) NOT NULL, ALT VARCHAR(255) DEFAULT NULL, MEDIA_ID INT NOT NULL, LANGUE_ID INT NOT NULL, INDEX IDX_30D0B54F14E107D9 (MEDIA_ID), INDEX IDX_30D0B54F5622E2C2 (LANGUE_ID), PRIMARY KEY(MEDIA_ID, LANGUE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_media_alt_translation ADD CONSTRAINT FK_30D0B54F14E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_media_alt_translation ADD CONSTRAINT FK_30D0B54F5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE psa_media_alt_translation');
    }
}
