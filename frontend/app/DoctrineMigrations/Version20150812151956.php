<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150812151956 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_zone_template SET ZONE_ID = '828' WHERE ZONE_TEMPLATE_ID = 4496");
        $this->addSql("DELETE FROM psa_zone where ZONE_ID = 838");
        $this->addSql("UPDATE psa_zone SET ZONE_BO_PATH = 'Cms_Page_Ndp_Pf23Rangebar', ZONE_FO_PATH = 'Pf23RangeBarStrategy' WHERE ZONE_ID = 828");
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_PF23_RANGEBAR"
                )'
            );
        }

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (838, 1, 'NDP_PF23_RANGEBAR', 0, NULL, 'Cms_Page_Ndp_Pf23Rangebar', NULL, 0, 0, 0, NULL, NULL, 28, 0, '')");
        $this->addSql("UPDATE psa_zone_template SET ZONE_ID = '838' WHERE ZONE_TEMPLATE_ID = 4496");

    }
}
