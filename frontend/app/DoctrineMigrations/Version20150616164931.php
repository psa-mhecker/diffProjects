<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150616164931 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (830, 1, 'NDP_PF2_PRESENTATION_SHOWROOM', 0, NULL, 'Cms_Page_Ndp_Pf2PresentationShowroom', NULL, 0, 0, 0, NULL, NULL, 28, 0, '')");

        $this->addSql("INSERT INTO `psa_zone_template` VALUES "
            . "(4438,'NDP_PC83_ACCESSORIES_CONTENT',290,150,820,1,NULL,NULL,NULL,30),"
            . "(4439,'NDP_PN18_IFRAME',290,150,824,1,NULL,NULL,NULL,30),"
            . "(4440,'NDP_PN21_FULL_USP',290,150,825,1,NULL, NULL,NULL,30),"
            . "(4441,'NDP_PN14_CONFISHOW_NAVIGATION',290,150,822,1,NULL,NULL,NULL,30),"
            . "(4442,'NDP_PC60_SUMMARY_AND_SHOWROOM_CTA',290,150,818,1,NULL, NULL,NULL,30),"
            . "(4443,'NDP_PN15_CONFISHOW_HEADER',290,150,823,1,NULL,NULL,NULL,30),"
            . "(4444,'NDP_PF2_PRESENTATION_SHOWROOM',290,150,830,1,NULL, NULL,NULL,30)"
            . ";"
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID IN (4438, 4439, 4440, 4441, 4442, 4443, 4444)");
        $this->addSql("DELETE FROM `psa_zone` WHERE ZONE_ID = 830");

    }
}
