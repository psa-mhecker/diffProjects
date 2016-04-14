<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150923103628 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_cta ADD LINKED_CTA INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_cta ADD CONSTRAINT FK_F0F9A976A37E6B35 FOREIGN KEY (LINKED_CTA) REFERENCES psa_cta (ID)');
        $this->addSql('CREATE INDEX IDX_F0F9A976A37E6B35 ON psa_cta (LINKED_CTA)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_cta DROP FOREIGN KEY FK_F0F9A976A37E6B35');
        $this->addSql('DROP INDEX IDX_F0F9A976A37E6B35 ON psa_cta');
        $this->addSql('ALTER TABLE psa_cta DROP LINKED_CTA');
    }
}
