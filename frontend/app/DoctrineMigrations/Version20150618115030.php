<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150618115030 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // ajout du la tranche pn3 dans une gabarit avec zone dynamique
        $this->addSql("INSERT INTO `psa_zone_template`
                      (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`)
                      VALUES (4445,'NDP_PN3_TOGGLE_ACCORDEON',290,150,790,22,22,NULL,NULL,30);");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $this->addSql('DELETE FROM psa_zone_template WHERE ZONE_TEMPLATE_ID = 4445 ');

    }
}
