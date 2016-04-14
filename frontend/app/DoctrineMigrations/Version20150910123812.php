<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150910123812 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE psa_cta ADD DOCUMENT_ID INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_cta ADD CONSTRAINT FK_F0F9A9767671AC3F FOREIGN KEY (DOCUMENT_ID) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('CREATE INDEX IDX_F0F9A9767671AC3F ON psa_cta (DOCUMENT_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_cta DROP FOREIGN KEY FK_F0F9A9767671AC3F');
        $this->addSql('DROP INDEX IDX_F0F9A9767671AC3F ON psa_cta');
        $this->addSql('ALTER TABLE psa_cta DROP DOCUMENT_ID');
    }
}
