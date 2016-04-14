<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150220084545 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        //add field
        $this->addSql('ALTER TABLE psa_cta ADD TARGET VARCHAR(50) NOT NULL, ADD LANGUE_ID INT DEFAULT NULL, ADD SITE_ID INT DEFAULT NULL');
        //add index
        $this->addSql('CREATE INDEX IDX_F0F9A9765622E2C2 ON psa_cta (LANGUE_ID)');
        $this->addSql('CREATE INDEX IDX_F0F9A976F1B5AEBC ON psa_cta (SITE_ID)');
        // add constraint
        $this->addSql('ALTER TABLE psa_cta ADD CONSTRAINT FK_F0F9A9765622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_cta ADD CONSTRAINT FK_F0F9A976F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        //remove constraint
        $this->addSql('ALTER TABLE psa_cta DROP FOREIGN KEY FK_F0F9A9765622E2C2');
        $this->addSql('ALTER TABLE psa_cta DROP FOREIGN KEY FK_F0F9A976F1B5AEBC');
        //remove index
        $this->addSql('DROP INDEX IDX_F0F9A9765622E2C2 ON psa_cta');
        $this->addSql('DROP INDEX IDX_F0F9A976F1B5AEBC ON psa_cta');
        //remove field
        $this->addSql('ALTER TABLE psa_cta DROP TARGET');
        $this->addSql('ALTER TABLE psa_cta DROP LANGUE_ID');
        $this->addSql('ALTER TABLE psa_cta DROP SITE_ID');


    }
}
