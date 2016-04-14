<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150806150953 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE psa_page_zone ADD DISPLAY_ON_FO tinyint(1) default 1;");
        $this->addSql("ALTER TABLE psa_page_multi_zone ADD DISPLAY_ON_FO tinyint(1) default 1;");
            
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE psa_page_zone DROP DISPLAY_ON_FO");
        $this->addSql("ALTER TABLE psa_page_multi_zone DROP DISPLAY_ON_FO");
    }
}
