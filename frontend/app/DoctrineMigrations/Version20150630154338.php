<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150630154338 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_pdv_service ADD CONSTRAINT FK_8F6B6113F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_pdv_service ADD CONSTRAINT FK_8F6B61135622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_pdv_service ADD CONSTRAINT FK_8F6B611314E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('CREATE INDEX IDX_8F6B6113F1B5AEBC ON psa_pdv_service (SITE_ID)');
        $this->addSql('CREATE INDEX IDX_8F6B61135622E2C2 ON psa_pdv_service (LANGUE_ID)');
        $this->addSql('CREATE INDEX IDX_8F6B611314E107D9 ON psa_pdv_service (MEDIA_ID)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F6B61138E800526 ON psa_pdv_service (PDV_SERVICE_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_pdv_service DROP FOREIGN KEY FK_8F6B6113F1B5AEBC');
        $this->addSql('ALTER TABLE psa_pdv_service DROP FOREIGN KEY FK_8F6B61135622E2C2');
        $this->addSql('ALTER TABLE psa_pdv_service DROP FOREIGN KEY FK_8F6B611314E107D9');
        $this->addSql('DROP INDEX IDX_8F6B6113F1B5AEBC ON psa_pdv_service');
        $this->addSql('DROP INDEX IDX_8F6B61135622E2C2 ON psa_pdv_service');
        $this->addSql('DROP INDEX IDX_8F6B611314E107D9 ON psa_pdv_service');
        $this->addSql('DROP INDEX uniq_8f6b61138e800526 ON psa_pdv_service');
    }
}
