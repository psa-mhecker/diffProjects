<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150922150618 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_media DROP COLUMN MEDIA_ALT');
        $this->addSql('ALTER TABLE psa_media DROP COLUMN MEDIA_TITLE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE  psa_media ADD MEDIA_ALT varchar(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE  psa_media ADD MEDIA_TITLE varchar(255) DEFAULT '-Sans nom-'");
    }
}
