<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160118104027 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
		              ('6090', 'NDP_PF17_FORM', '1015', '150', '837', '23', '23', NULL, NULL, '-2')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
	    $this->addSql("DELETE FROM `psa_zone_template` where `ZONE_TEMPLATE_ID` = 6090");
    }
}
