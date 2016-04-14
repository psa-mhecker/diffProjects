<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150427115348 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta_cta ADD PAGE_ZONE_CTA_CTA_TYPE VARCHAR(50) NOT NULL');

        $this->addSql('ALTER TABLE psa_page_zone_multi_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE)');

        $this->addSql('ALTER TABLE psa_content_version_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, CONTENT_ID, LANGUE_ID, CONTENT_VERSION)');

        $this->addSql('ALTER TABLE psa_page_zone_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID)');

        $this->addSql('ALTER TABLE psa_page_multi_zone_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER)');

        $this->addSql('ALTER TABLE psa_page_zone_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID)');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta ADD PAGE_ZONE_CTA_CTA_TYPE VARCHAR(50) NOT NULL');

        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE)');

        $this->addSql('ALTER TABLE psa_content_version_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_content_version_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, CONTENT_ID, LANGUE_ID, CONTENT_VERSION)');
        $this->addSql('ALTER TABLE psa_content_version_cta_cta ADD PAGE_ZONE_CTA_CTA_TYPE VARCHAR(50) NOT NULL');

        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta ADD PAGE_ZONE_CTA_CTA_TYPE VARCHAR(50) NOT NULL');

        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ZONE_CTA_TYPE, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta ADD PAGE_ZONE_CTA_CTA_TYPE VARCHAR(50) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_content_version_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, CONTENT_ID, LANGUE_ID, CONTENT_VERSION, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_content_version_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_content_version_cta_cta DROP PAGE_ZONE_CTA_CTA_TYPE');
        $this->addSql('ALTER TABLE psa_content_version_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, CONTENT_ID, LANGUE_ID, CONTENT_VERSION, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta DROP PAGE_ZONE_CTA_CTA_TYPE');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta_cta DROP PAGE_ZONE_CTA_CTA_TYPE');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_zone_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta DROP PAGE_ZONE_CTA_CTA_TYPE');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE, PAGE_ZONE_CTA_TYPE)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta DROP PAGE_ZONE_CTA_CTA_TYPE');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta ADD PRIMARY KEY (PAGE_ZONE_CTA_CTA_ID, PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TYPE, PAGE_ZONE_CTA_TYPE)');
    }
}
