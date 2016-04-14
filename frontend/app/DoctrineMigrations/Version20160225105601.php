<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160225105601 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //CLEAN résiduts du gabarits HP initial
        $this->addSql("set foreign_key_checks=0");

        $this->addSql("DELETE FROM `psa_page_version` WHERE `TEMPLATE_PAGE_ID` in (363)");
        $this->addSql("DELETE FROM `psa_page` WHERE `PAGE_ID` in (4114,4137)");


        //CLEAN Gabarits Home page, CNT_BASIQUE, CAR_SELECTOR, TP_TECHNO, DEALER_LOCATOR

        $tablesToUpdate = array('psa_page_zone','psa_page_zone_content','psa_page_zone_cta','psa_page_zone_cta_cta','psa_page_zone_media','psa_page_zone_multi','psa_page_zone_multi_cta','psa_page_zone_multi_cta_cta','psa_page_zone_multi_multi','psa_page_zone_vehicule','psa_user_page_zone','psa_user_zone_template');
        $listGabarits = array("1530"=>"363", "1538"=>"377", "1534"=>"1002", "1539"=>"1001", "1518"=>"364");

        foreach ($listGabarits as $gabaritCible => $gabaritInitial) {
            foreach($tablesToUpdate as $table){
                $this->addSql("UPDATE `$table` z
                                JOIN psa_zone_template a ON a.ZONE_TEMPLATE_ID  = z.ZONE_TEMPLATE_ID
                                JOIN psa_zone_template b on b.ZONE_TEMPLATE_LABEL LIKE concat('%',a.ZONE_TEMPLATE_LABEL,'%') AND b.TEMPLATE_PAGE_ID in ($gabaritCible)
                            SET z.ZONE_TEMPLATE_ID = b.ZONE_TEMPLATE_ID
                            where a.TEMPLATE_PAGE_ID in ($gabaritInitial)");
            }

            //UPDATE TEMPLATE_PAGE_ID
            $this->addSql("UPDATE psa_page_version SET `TEMPLATE_PAGE_ID` = $gabaritCible WHERE `TEMPLATE_PAGE_ID` IN ($gabaritInitial)");
        }

        //DELETE gabarits showroom 378,1015
        $templatesTables = array('psa_template_page','psa_template_page_area','psa_zone_template');
        $listGabaritsInitiaux = implode(', ', array_values($listGabarits));
        foreach ($templatesTables as $table ) {
            $this->addSql("DELETE FROM `$table` WHERE `TEMPLATE_PAGE_ID` IN ($listGabaritsInitiaux)");

        }

        //CLEAN UPDATED TABLES
        foreach ($tablesToUpdate as $table ) {
            $this->addSql("DELETE FROM `$table` WHERE `ZONE_TEMPLATE_ID` NOT IN (
                            SELECT `ZONE_TEMPLATE_ID` FROM `psa_zone_template`)");
        }

        $this->addSql("set foreign_key_checks=1");

    }



    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
