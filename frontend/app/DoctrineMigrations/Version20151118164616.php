<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151118164616 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_zone_template` WHERE TEMPLATE_PAGE_ID IN (1505)");
        $this->addSql("DELETE FROM `psa_template_page` WHERE TEMPLATE_PAGE_ID IN (1505)");
        $this->addSql("DELETE FROM `psa_template_page_area` WHERE TEMPLATE_PAGE_ID IN (1505)");

        $this->addSql("REPLACE INTO `psa_template_page` (`TEMPLATE_PAGE_ID`,`SITE_ID`,`PAGE_TYPE_ID`,`TEMPLATE_PAGE_LABEL`,`TEMPLATE_PAGE_GENERAL`) VALUES
                        (1515,2,33,'AGILE - DEMO - Sprint2 - NavLight',NULL)");

        $this->addSql("DELETE from `psa_zone_template` where ZONE_TEMPLATE_ID=5014");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
