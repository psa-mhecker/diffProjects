<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151112122415 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_page_zone ADD ZONE_TIMER_SPEED TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_page_multi_zone ADD ZONE_TIMER_SPEED TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_page_zone DROP ZONE_TIMER_SPEED');
        $this->addSql('ALTER TABLE psa_page_multi_zone DROP ZONE_TIMER_SPEED');
    }
}
