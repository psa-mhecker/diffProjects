<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150702165030 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //Ajout bloc d'admin uniquement en BO
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
                (832, 1, 'NDP_PT22_MY_PEUGEOT', 0, NULL, 'Cms_Page_Ndp_Pt22MyPeugeot','', 0, 0, 0, NULL, NULL, 28, 0, '')
            ");
        //Transformation du bloc PT22 Actuel en mode FO uniquement et automatique
        $this->addSql("UPDATE `psa_zone` SET  ZONE_BO_PATH= '',ZONE_TYPE_ID=2  WHERE ZONE_ID = 826 ");
        // Ajout a la page général pour utilisé le nouveau bloc BO
        $this->addSql("INSERT INTO psa_zone_template
              (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES"
            . " (20, 'NDP_PT22_MY_PEUGEOT', '150', '121', '832', 5, NULL, NULL, NULL, '30');");

       $this->addSql("UPDATE psa_label_langue_site  SET LABEL_TRANSLATE = 'PT22 Expand MyPeugeot _ cross section' WHERE LABEL_ID = 'NDP_PT22_MY_PEUGEOT'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM  psa_zone_template WHERE ZONE_ID = 832");
        $this->addSql("DELETE FROM  `psa_zone` WHERE ZONE_ID = 832");

    }
}
