<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150526140800 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_site_webservice ADD service_key VARCHAR(255) NOT NULL, ADD response_type VARCHAR(255) NOT NULL, ADD response_format VARCHAR(255) NOT NULL, ADD cache_ttl INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_site_webservice ADD CONSTRAINT FK_CBD64E54F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_site_webservice ADD CONSTRAINT FK_CBD64E546D5AD892 FOREIGN KEY (ws_id) REFERENCES psa_liste_webservices (ws_id)');
        $this->addSql('CREATE INDEX IDX_CBD64E54F1B5AEBC ON psa_site_webservice (SITE_ID)');
        $this->addSql('CREATE INDEX IDX_CBD64E546D5AD892 ON psa_site_webservice (ws_id)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_site_webservice DROP FOREIGN KEY FK_CBD64E54F1B5AEBC');
        $this->addSql('ALTER TABLE psa_site_webservice DROP FOREIGN KEY FK_CBD64E546D5AD892');
        $this->addSql('DROP INDEX IDX_CBD64E54F1B5AEBC ON psa_site_webservice');
        $this->addSql('DROP INDEX IDX_CBD64E546D5AD892 ON psa_site_webservice');
        $this->addSql('ALTER TABLE psa_site_webservice DROP service_key, DROP response_type, DROP response_format, DROP cache_ttl');

    }
}
