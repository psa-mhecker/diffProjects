<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160302114409 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("DELETE from `psa_page_version` where `PAGE_ID` IN('3324')");
        $this->addSql("DELETE from `psa_page_multi_zone` where `PAGE_ID` IN('3324')");
        $this->addSql("DELETE from `psa_page` where `PAGE_ID` IN('3324')");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
