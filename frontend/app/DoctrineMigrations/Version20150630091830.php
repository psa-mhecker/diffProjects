<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150630091830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //Ajout bloc d'admin uniquement en BO
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
                (831, 1, 'NDP_PT3_JE_VEUX', 0, NULL, 'Cms_Page_Ndp_Pt3JeVeux','', 0, 0, 0, NULL, NULL, 28, 0, '')
            ");
        //Transformation du bloc PT3 Actuel en mode FO uniquement et automatique
        $this->addSql("UPDATE `psa_zone` SET  ZONE_BO_PATH= '',ZONE_TYPE_ID=2  WHERE ZONE_ID = 801 ");
        // mise a jour de la page général pour utilisé le nouveau bloc BO
        $this->addSql("UPDATE psa_zone_template set ZONE_ID=831 WHERE ZONE_ID=801 AND TEMPLATE_PAGE_ID = 150");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE psa_zone_template set ZONE_ID=801 WHERE ZONE_ID=831 AND TEMPLATE_PAGE_ID = 150");
        $this->addSql("DELETE FROM  `psa_zone` WHERE ZONE_ID = 831");

    }
}
