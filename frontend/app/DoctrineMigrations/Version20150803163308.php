<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150803163308 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
       
        $this->addSql("REPLACE INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (3300, 1, 'NDP_PF33_CAR_COMPATIBILITY', 0, NULL, 'Cms_Page_Ndp_Pf33CarCompatibility', 'Pf33CarCompatibilityStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')");
        $this->addSql("REPLACE INTO `psa_zone_template` VALUES "
            ."(3300,'NDP_PF33_CAR_COMPATIBILITY',290,150,3300,1,NULL, NULL,NULL,30)"
            .";"
        );
         $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_PF33_CAR_COMPATIBILITY', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_SHOW_DETAILS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_VERSION', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_PF33_CAR_COMPATIBILITY', 1, 1, 'PF33 - Car compatibility table_ connected services specific'),
            ('NDP_SHOW_DETAILS', 1, 1, 'Affichage des détails'),
            ('NDP_VERSION', 1, 1, 'Version')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_ID` = 3300");
        $this->addSql("DELETE FROM `psa_zone` WHERE `ZONE_ID` = 3300");
      
           $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                "NDP_PF33_CAR_COMPATIBILITY",
                "NDP_SHOW_DETAILS",
                "NDP_VERSION"
                )');
        }
        
    }
}
