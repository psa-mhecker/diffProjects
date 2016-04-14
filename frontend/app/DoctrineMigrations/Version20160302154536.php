<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160302154536 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL` = concat('AGILE - ',TEMPLATE_PAGE_LABEL)
                      WHERE `TEMPLATE_PAGE_ID` IN (362, 366, 367, 385, 1000) AND `TEMPLATE_PAGE_LABEL` NOT LIKE 'AGILE%'");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL` = REPLACE (TEMPLATE_PAGE_LABEL, 'AGILE - ', '')
                      WHERE `TEMPLATE_PAGE_ID` IN (362, 366, 367, 385, 1000)");

    }
}
