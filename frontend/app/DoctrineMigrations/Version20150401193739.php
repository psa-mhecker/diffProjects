<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401193739 extends AbstractMigration
{
      /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //CTA ID can be null :( 
        $this->addSql('ALTER TABLE psa_page_zone_cta MODIFY `CTA_ID` int(11) NULL');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta MODIFY `CTA_ID` int(11) NULL');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta MODIFY `CTA_ID` int(11) NULL');


        //missing part of unique key
        $this->addSql('ALTER TABLE psa_page_zone_cta DROP PRIMARY KEY, ADD PRIMARY KEY(PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta DROP PRIMARY KEY, ADD PRIMARY KEY(PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER, PAGE_ZONE_CTA_TYPE)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $this->addSql('ALTER TABLE psa_page_zone_cta DROP PRIMARY KEY, ADD PRIMARY KEY(PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta DROP PRIMARY KEY, ADD PRIMARY KEY(PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER)');

    }
}
