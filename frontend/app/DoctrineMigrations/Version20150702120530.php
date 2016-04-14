<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150702120530 extends AbstractMigration
{

    private  function getSqlTemplatePageId() {

       return "SELECT t.TEMPLATE_PAGE_ID  FROM psa_zone z JOIN psa_zone_template zt ON z.ZONE_ID=zt.ZONE_ID JOIN psa_template_page t ON zt.TEMPLATE_PAGE_ID= t.TEMPLATE_PAGE_ID WHERE zt.AREA_ID=121 AND z.ZONE_LABEL LIKE '%PT21%' AND t.TEMPLATE_PAGE_LABEL NOT LIKE '%PT21%' ";

    }
    private  function getSqlZoneId() {

        return " (SELECT z.zone_id   FROM psa_zone z WHERE z.ZONE_LABEL ='NDP_PT21_NAVIGATION') ";

    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // cleaning all area
        $this->addSql('UPDATE psa_area SET AREA_HEAD ="",AREA_FOOT = "" ');
        // Deplacement des toutes les zone d'une ligne vers le bas dans les gabarit qui contiennt pt21
        $this->addSql("UPDATE  psa_template_page_area SET  TEMPLATE_PAGE_AREA_ORDER =  TEMPLATE_PAGE_AREA_ORDER + 1, LIGNE =  LIGNE +1 WHERE  TEMPLATE_PAGE_ID IN (".$this->getSqlTemplatePageId().") ");
        // creation de la nouvelle zone pour pt21
        $this->addSql("INSERT INTO psa_area (AREA_ID,AREA_LABEL,AREA_PATH,AREA_HEAD,AREA_FOOT,AREA_DROPPABLE) VALUES(10,'Peugeot - Navigation',NULL,'','<div class=\"body\">',  NULL )");
        // ajouter cette zone a tous les gabarits adéquat
        $this->addSql('INSERT INTO psa_template_page_area (TEMPLATE_PAGE_ID, AREA_ID ,TEMPLATE_PAGE_AREA_ORDER, LIGNE, COLONNE, LARGEUR,HAUTEUR, IS_DROPPABLE)
                       SELECT t.TEMPLATE_PAGE_ID,10,1,1,1,4,1,NULL  FROM psa_zone z JOIN psa_zone_template zt ON z.ZONE_ID=zt.ZONE_ID JOIN psa_template_page t ON zt.TEMPLATE_PAGE_ID= t.TEMPLATE_PAGE_ID WHERE zt.AREA_ID=121 AND z.ZONE_LABEL LIKE "%PT21%" AND t.TEMPLATE_PAGE_LABEL NOT LIKE "%PT21%"  ');
        // deplacement de la trache pt21 dans la zone
        $this->addSql('UPDATE psa_zone_template SET AREA_ID = 10 WHERE AREA_ID=121 AND  ZONE_ID = '. $this->getSqlZoneId());
        // ajout balise fermant au footer
        $this->addSql('UPDATE psa_area SET AREA_FOOT = "</div>" WHERE AREA_ID = 122');



    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // cleaning all area
        $this->addSql('UPDATE psa_area SET AREA_HEAD ="",AREA_FOOT = "" ');
        // deplacement de la trache pt21 dans la zone head
        $this->addSql('UPDATE psa_zone_template SET AREA_ID = 121 WHERE AREA_ID=10 AND  ZONE_ID = '. $this->getSqlZoneId());
        // supression de la zone dans les gabarit
        $this->addSql("DELETE FROM psa_template_page_area WHERE AREA_ID=10");
        // remove new area
        $this->addSql("DELETE FROM psa_area WHERE AREA_ID=10");
        // move back area
        $this->addSql("UPDATE  psa_template_page_area SET  TEMPLATE_PAGE_AREA_ORDER =  TEMPLATE_PAGE_AREA_ORDER - 1, LIGNE =  LIGNE - 1 WHERE  TEMPLATE_PAGE_ID IN (".$this->getSqlTemplatePageId().") ");

    }
}
