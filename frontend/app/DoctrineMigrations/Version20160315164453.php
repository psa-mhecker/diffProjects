<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160315164453 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE psa_page_version pv INNER JOIN psa_page p ON pv.PAGE_ID=p.PAGE_ID AND pv.LANGUE_ID=p.LANGUE_ID SET pv.TEMPLATE_PAGE_ID =1530 WHERE p.PAGE_PARENT_ID IS NULL AND pv.TEMPLATE_PAGE_ID IS NULL AND p.PAGE_GENERAL=0');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
