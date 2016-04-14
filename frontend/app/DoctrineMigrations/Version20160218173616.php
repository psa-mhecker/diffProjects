<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160218173616 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //CLEAN des gabarits showroom
        $this->addSql("set foreign_key_checks=0");

        //UPDATE ZONE_TEMPLATE_ID
        $tablesToUpdate = array('psa_page_zone','psa_page_zone_content','psa_page_zone_cta','psa_page_zone_cta_cta','psa_page_zone_media','psa_page_zone_multi','psa_page_zone_multi_cta','psa_page_zone_multi_cta_cta','psa_page_zone_multi_multi','psa_page_zone_vehicule','psa_user_page_zone','psa_user_zone_template');
        foreach($tablesToUpdate as $table){
            $this->addSql("UPDATE `$table` z
                            JOIN psa_zone_template a ON a.ZONE_TEMPLATE_ID  = z.ZONE_TEMPLATE_ID
                            JOIN psa_zone_template b on b.ZONE_TEMPLATE_LABEL LIKE concat('%',a.ZONE_TEMPLATE_LABEL,'%') AND b.TEMPLATE_PAGE_ID in (1533)
                        SET z.ZONE_TEMPLATE_ID = b.ZONE_TEMPLATE_ID
                        where a.TEMPLATE_PAGE_ID in (378)");
        }

        $tablesToUpdate2 = array('psa_page_zone','psa_page_zone_content','psa_page_zone_cta','psa_page_zone_cta_cta','psa_page_zone_media','psa_page_zone_multi_cta','psa_page_zone_multi_cta_cta','psa_page_zone_multi_multi','psa_page_zone_vehicule','psa_user_page_zone','psa_user_zone_template');
        foreach($tablesToUpdate2 as $table){
            $this->addSql("UPDATE `$table` z
                            JOIN psa_zone_template a ON a.ZONE_TEMPLATE_ID  = z.ZONE_TEMPLATE_ID
                            JOIN psa_zone_template b on b.ZONE_TEMPLATE_LABEL LIKE concat('%',a.ZONE_TEMPLATE_LABEL,'%') AND b.TEMPLATE_PAGE_ID in (1533)
                        SET z.ZONE_TEMPLATE_ID = b.ZONE_TEMPLATE_ID
                        where a.TEMPLATE_PAGE_ID in (1015)");
        }

        //UPDATE TEMPLATE_PAGE_ID
        $this->addSql("UPDATE psa_page_version SET `TEMPLATE_PAGE_ID` = 1533 WHERE `TEMPLATE_PAGE_ID` IN ('378','1015')");


        //DELETE gabarits showroom 378,1015
        $templatesTables = array('psa_template_page','psa_template_page_area','psa_zone_template');
        foreach ($templatesTables as $table ) {
            $this->addSql("DELETE FROM `$table` WHERE `TEMPLATE_PAGE_ID` IN ('378','1015')");

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
