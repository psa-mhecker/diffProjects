<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150708152606 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE INDEX IDX_78A5F41F68469EFD ON psa_model_view_angle (LCDV4)');
        $this->addSql('ALTER TABLE psa_model_view_angle DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_model_view_angle ADD PRIMARY KEY (LCDV4, CODE)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_model_view_angle DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_model_view_angle ADD PRIMARY KEY (LCDV4)');
        $this->addSql('DROP INDEX IDX_78A5F41F68469EFD ON psa_model_view_angle');

    }
}
