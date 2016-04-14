<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151209124250 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE from  `psa_page_multi_zone` where page_id not in (select page_id from `psa_page`)");
        $this->addSql("ALTER TABLE `psa_page_multi_zone`
                        ADD CONSTRAINT `FK_PAGE_ZONE_16` FOREIGN KEY (`PAGE_ID`) REFERENCES `psa_page` (`PAGE_ID`)");
        $this->addSql("DELETE from `psa_page_version` where page_id not in (select page_id from `psa_page`)");
        $this->addSql("ALTER TABLE `psa_page_version`
                        ADD CONSTRAINT `FK_PAGE_VERSION_09` FOREIGN KEY (`PAGE_ID`) REFERENCES `psa_page` (`PAGE_ID`)");
        $this->addSql("DELETE from `psa_page_multi_zone_content` where page_id not in (select page_id from `psa_page`)");
        $this->addSql("ALTER TABLE `psa_page_multi_zone_content`
                        ADD CONSTRAINT `FK_PAGE_MULTI_ZONE_CONTENT_11` FOREIGN KEY (`PAGE_ID`) REFERENCES `psa_page` (`PAGE_ID`)");
        $this->addSql("DELETE from `psa_page_order` where page_id not in (select page_id from `psa_page`)");
        $this->addSql("ALTER TABLE `psa_page_order`
                        ADD CONSTRAINT `FK_PAGE_ORDER_11` FOREIGN KEY (`PAGE_ID`) REFERENCES `psa_page` (`PAGE_ID`)");
        $this->addSql("DELETE from `psa_page_zone` where page_id not in (select page_id from `psa_page`)");
        $this->addSql("DELETE from `psa_page_version` where PAGE_ID in ('3783','3353','3308','3307','3352')");
        $this->addSql("DELETE from `psa_page_order` where PAGE_ID in ('3783','3353','3308','3307','3352')");
        $this->addSql("DELETE from `psa_page_multi_zone` where PAGE_ID in ('3783','3353','3308','3307','3352')");
        $this->addSql("DELETE from `psa_page` where PAGE_ID in ('3783','3353','3308','3307','3352')");
        $this->addSql("DELETE from `psa_page_version` where TEMPLATE_PAGE_ID IN
                        (SELECT TEMPLATE_PAGE_ID from `psa_template_page` where PAGE_TYPE_ID in (27,29,30,32,5))");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE `psa_page_multi_zone` DROP FOREIGN KEY FK_PAGE_ZONE_16");
        $this->addSql("ALTER TABLE `psa_page_version` DROP FOREIGN KEY FK_PAGE_VERSION_09");
        $this->addSql("ALTER TABLE `psa_page_multi_zone_content` DROP FOREIGN KEY FK_PAGE_MULTI_ZONE_CONTENT_11");
        $this->addSql("ALTER TABLE `psa_page_order` DROP FOREIGN KEY FK_PAGE_ORDER_11");

    }
}
