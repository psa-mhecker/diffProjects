<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150203155249 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_page_zone_multi_cta CHANGE IS_ACTIVE IS_ACTIVE TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE psa_content_version_cta CHANGE IS_ACTIVE IS_ACTIVE TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE psa_page_zone_cta CHANGE IS_ACTIVE IS_ACTIVE TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_content_version_cta CHANGE IS_ACTIVE IS_ACTIVE TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE psa_page_zone_cta CHANGE IS_ACTIVE IS_ACTIVE TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta CHANGE IS_ACTIVE IS_ACTIVE TINYINT(1) NOT NULL');
    }
}
