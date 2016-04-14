<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150129161842 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_page_zone_multi_cta ADD CTA_REF_ORDER INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD CTA_REF_ORDER INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CTA_REF_ORDER INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_content_version_cta DROP CTA_REF_ORDER');
        $this->addSql('ALTER TABLE psa_page_zone_cta DROP CTA_REF_ORDER');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta DROP CTA_REF_ORDER');
    }
}
