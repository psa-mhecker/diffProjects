<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150611104535 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //,
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (826,1,'NDP_PT22_MY_PEUGEOT',0,NULL,'Cms_Page_Ndp_Pt22MyPeugeot','Pt22MyPeugeotStrategy',0,0,0,NULL,NULL,28,0,''),
            (827,1,'NDP_PF30_POPIN_CODE_POSTAL',0,NULL,'Cms_Page_Ndp_Pf30PopinCodePostal','Pf30PopinCodePostalStrategy',0,0,0,NULL,NULL,28,0,'')
        ");

       $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_PT22_MY_PEUGEOT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PF30_POPIN_CODE_POSTAL', NULL, 2, NULL, NULL, 1, NULL)
            ");

       $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_PT22_MY_PEUGEOT', 1, 1, 'Expand MyPeugeot _ cross section'),
            ('NDP_PF30_POPIN_CODE_POSTAL', 1, 1, 'Post code pop in')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM `psa_zone`  WHERE ZONE_ID IN (826,827)');

        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN (
            "NDP_PT22_MY_PEUGEOT",
            "NDP_PF30_POPIN_CODE_POSTAL"
            )');
        }

    }
}
