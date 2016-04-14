<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150715174238 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

			$this->addSql("INSERT INTO psa_zone_template
              (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES"
            . " (3100, 'NDP_PC84_APP_LIST_DYNAMIC_CONTENT', 290, 150, 821, 23, NULL, NULL, NULL, 30)");
   }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

			$this->addSql('DELETE FROM psa_zone_template WHERE ZONE_TEMPLATE_ID= 3100');
    }
}
