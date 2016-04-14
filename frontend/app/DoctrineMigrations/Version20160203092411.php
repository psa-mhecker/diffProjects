<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160203092411 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE psa_page_version MODIFY COLUMN PAGE_TITLE_BO VARCHAR(255) DEFAULT "" NOT NULL');
        $this->addSql('ALTER TABLE psa_page_version MODIFY COLUMN PAGE_TITLE VARCHAR(255) DEFAULT "" NOT NULL');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE psa_page_version MODIFY COLUMN PAGE_TITLE_BO VARCHAR(255) DEFAULT "-Sans nom-" NOT NULL');
        $this->addSql('ALTER TABLE psa_page_version MODIFY COLUMN PAGE_TITLE VARCHAR(255) DEFAULT "-Sans nom-" NOT NULL');

    }
}
