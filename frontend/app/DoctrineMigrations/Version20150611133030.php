<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150611133030 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //,
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (828,1,'NDP_PF23_RANGE_BAR',0,NULL,'Cms_Page_Ndp_Pf23RangeBar','Pf23RangeBarStrategy',0,0,0,NULL,NULL,28,0,''),
            (829,1,'NDP_PC73_MEGA_BANNIERE_DYNAMIQUE',0,NULL,'Cms_Page_Ndp_Pc73MegaBanniereDynamique','Pc73MegaBanniereDynamique',0,0,0,NULL,NULL,28,0,'')
        ");

       $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_PF23_RANGE_BAR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PC73_MEGA_BANNIERE_DYNAMIQUE', NULL, 2, NULL, NULL, 1, NULL)
            ");

       $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_PF23_RANGE_BAR', 1, 1, 'Car range bar_ specific HP_only desktop'),
            ('NDP_PC73_MEGA_BANNIERE_DYNAMIQUE', 1, 1, 'Dynamic text message_desktop only_content')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM `psa_zone`  WHERE ZONE_ID IN (828,829)');

        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN (
            "NDP_PF23_RANGE_BAR",
            "NDP_PC73_MEGA_BANNIERE_DYNAMIQUE"

            )');
        }

    }
}
