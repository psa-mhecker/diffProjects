<?php

namespace Application\Migrations;

use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160405095803 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //AGILE - NDP_TP_PRESTATION_APV G22
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL` = 'AGILE - NDP_TP_PRESTATION_APV' WHERE `TEMPLATE_PAGE_ID` = 1531");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='9', `ZONE_TEMPLATE_MOBILE_ORDER` ='9' where `ZONE_TEMPLATE_ID` = 6088");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_MOBILE_ORDER` ='3' where `ZONE_TEMPLATE_ID` = 6116");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_MOBILE_ORDER` ='4' where `ZONE_TEMPLATE_ID` = 6117");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_MOBILE_ORDER` ='5' where `ZONE_TEMPLATE_ID` = 6089");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_MOBILE_ORDER` ='6' where `ZONE_TEMPLATE_ID` = 6114");

        $this->addSql("INSERT INTO `psa_zone_template` (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES
                        ('6113', 'NDP_PC7_DEUX_COLONNES', '1531', '150', '781', '7', '7', NULL, NULL, '-2'),
                        ('6115', 'NDP_PF11_RECHERCHE_POINT_DE_VENTE', '1531', '150', '812', '8', '8', NULL, NULL, '-2')");

        $this->upTranslations(
            array(
                'AGILE - NDP_TP_PRESTATION_APV' =>
                    array(
                        'expression' => "AGILE Prestation APV (G22)",
                        'bo' => 1,
                        'LANGUE_ID' => '1'
                    )
                )
            );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_ID` IN (6113,6115)");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER` ='3', `ZONE_TEMPLATE_MOBILE_ORDER` ='3' where `ZONE_TEMPLATE_ID` = 6088");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_MOBILE_ORDER` ='NULL' where `ZONE_TEMPLATE_ID` IN (6114,6116,6117,6089)");

        $this->downTranslations(
            array(
                'NDP_TP_PRESTATION_APV'
            )
        );
    }
}
