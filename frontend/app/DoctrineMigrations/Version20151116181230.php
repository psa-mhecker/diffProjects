<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151116181230 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // toutes les zones mobile on le même ordre que les zones web par defaut;
        $this->addSql('update psa_zone_template set  ZONE_TEMPLATE_MOBILE_ORDER = ZONE_TEMPLATE_ORDER ');
        // pour le showroom on doit inverser la position de pf2 et pn14 en mobile
        $this->addSql('update psa_zone_template set ZONE_TEMPLATE_MOBILE_ORDER=7 WHERE ZONE_TEMPLATE_ID =4463 ');
        $this->addSql('update psa_zone_template set ZONE_TEMPLATE_MOBILE_ORDER=6 WHERE ZONE_TEMPLATE_ID =4464 ');
        // modification de la vue pour ajouter le champs zone_mobile_order
        $this->addSql("CREATE OR REPLACE

            VIEW `psa_page_areas_blocks`
            AS SELECT concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`) AS `AREA_UID`,
               concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `BLOCK_UID`,
               concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `PERMANENT_ID`,
               `pv`.`PAGE_ID` AS `PAGE_ID`,
               `pv`.`PAGE_VERSION` AS `PAGE_VERSION`,
               `pv`.`LANGUE_ID` AS `LANGUE_ID`,
               `pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,
               `zt`.`AREA_ID` AS `AREA_ID`,
               `a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,
               `zt`.`ZONE_ID` AS `ZONE_ID`,
               `zt`.`ZONE_TEMPLATE_ID` AS `BLOCK_PAGE_DATA_UID`,
               NULL AS `MULTI_ZONE_UID`,
               `tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,
               `zt`.`ZONE_TEMPLATE_ORDER` AS `ZONE_ORDER`,
               `zt`.`ZONE_TEMPLATE_MOBILE_ORDER` AS `ZONE_MOBILE_ORDER`,
               `z`.`ZONE_LABEL`,
               `p`.`SITE_ID`
            FROM (((`psa_page_version` `pv`
                JOIN `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`)))
                JOIN `psa_zone_template` `zt` on(((`tpa`.`TEMPLATE_PAGE_ID` = `zt`.`TEMPLATE_PAGE_ID`) AND (`tpa`.`AREA_ID` = `zt`.`AREA_ID`))))
                JOIN `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`)))
                JOIN `psa_zone` `z` on (`z`.`ZONE_ID` = `zt`.`ZONE_ID` )
                JOIN `psa_page` `p` on (`p`.`PAGE_ID` = `pv`.`PAGE_ID` )
            WHERE isnull(`a`.`AREA_DROPPABLE`)
            UNION ALL (
                SELECT concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`) AS `AREA_UID`,
                       concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`ZONE_ORDER`) AS `BLOCK_UID`,
                       concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`UID`) AS `PERMANENT_ID`,
                       `pv`.`PAGE_ID` AS `PAGE_ID`,
                       `pv`.`PAGE_VERSION` AS `PAGE_VERSION`,
                       `pv`.`LANGUE_ID` AS `LANGUE_ID`,
                       `pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,
                       `a`.`AREA_ID` AS `AREA_ID`,
                       `a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,
                       `pmz`.`ZONE_ID` AS `ZONE_ID`,
                       `zt`.`ZONE_TEMPLATE_ID` AS `BLOCK_PAGE_DATA_UID`,
                       `pmz`.`UID` AS `MULTI_ZONE_UID`,
                       `tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,
                       `pmz`.`ZONE_ORDER` AS `ZONE_ORDER`,
                       `pmz`.`ZONE_ORDER` AS `ZONE_MOBILE_ORDER`,
                       `z`.`ZONE_LABEL`,
                       `p`.`SITE_ID`
                FROM (((`psa_page_version` `pv`
                        JOIN `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`)))
                        JOIN `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`)))
                        JOIN `psa_page_multi_zone` `pmz` on(((`pmz`.`PAGE_ID` = `pv`.`PAGE_ID`)
                            AND (`pmz`.`LANGUE_ID` = `pv`.`LANGUE_ID`)
                            AND (`pmz`.`PAGE_VERSION` = `pv`.`PAGE_VERSION`)
                            AND (`tpa`.`AREA_ID` = `pmz`.`AREA_ID`))))
                        JOIN `psa_zone` `z` on (`z`.`ZONE_ID` = `pmz`.`ZONE_ID` )
                        JOIN `psa_page` `p` on (`p`.`PAGE_ID` = `pv`.`PAGE_ID` )
                        JOIN `psa_zone_template` `zt` on (`zt`.`AREA_ID` = `a`.`AREA_ID` and `z`.`ZONE_ID`= `zt`.`ZONE_ID` AND `zt`.`TEMPLATE_PAGE_ID`=`pv`.`TEMPLATE_PAGE_ID`)
                WHERE (`a`.`AREA_DROPPABLE` = 1)
            )"
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE OR REPLACE

            VIEW `psa_page_areas_blocks`
            AS SELECT concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`) AS `AREA_UID`,
               concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `BLOCK_UID`,
               concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `PERMANENT_ID`,
               `pv`.`PAGE_ID` AS `PAGE_ID`,
               `pv`.`PAGE_VERSION` AS `PAGE_VERSION`,
               `pv`.`LANGUE_ID` AS `LANGUE_ID`,
               `pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,
               `zt`.`AREA_ID` AS `AREA_ID`,
               `a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,
               `zt`.`ZONE_ID` AS `ZONE_ID`,
               `zt`.`ZONE_TEMPLATE_ID` AS `BLOCK_PAGE_DATA_UID`,
               NULL AS `MULTI_ZONE_UID`,
               `tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,
               `zt`.`ZONE_TEMPLATE_ORDER` AS `ZONE_ORDER`,
               `z`.`ZONE_LABEL`,
               `p`.`SITE_ID`
            FROM (((`psa_page_version` `pv`
                JOIN `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`)))
                JOIN `psa_zone_template` `zt` on(((`tpa`.`TEMPLATE_PAGE_ID` = `zt`.`TEMPLATE_PAGE_ID`) AND (`tpa`.`AREA_ID` = `zt`.`AREA_ID`))))
                JOIN `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`)))
                JOIN `psa_zone` `z` on (`z`.`ZONE_ID` = `zt`.`ZONE_ID` )
                JOIN `psa_page` `p` on (`p`.`PAGE_ID` = `pv`.`PAGE_ID` )
            WHERE isnull(`a`.`AREA_DROPPABLE`)
            UNION ALL (
                SELECT concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`) AS `AREA_UID`,
                       concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`ZONE_ORDER`) AS `BLOCK_UID`,
                       concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`UID`) AS `PERMANENT_ID`,
                       `pv`.`PAGE_ID` AS `PAGE_ID`,
                       `pv`.`PAGE_VERSION` AS `PAGE_VERSION`,
                       `pv`.`LANGUE_ID` AS `LANGUE_ID`,
                       `pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,
                       `a`.`AREA_ID` AS `AREA_ID`,
                       `a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,
                       `pmz`.`ZONE_ID` AS `ZONE_ID`,
                       `zt`.`ZONE_TEMPLATE_ID` AS `BLOCK_PAGE_DATA_UID`,
                       `pmz`.`UID` AS `MULTI_ZONE_UID`,
                       `tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,
                       `pmz`.`ZONE_ORDER` AS `ZONE_ORDER`,
                       `z`.`ZONE_LABEL`,
                       `p`.`SITE_ID`
                FROM (((`psa_page_version` `pv`
                        JOIN `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`)))
                        JOIN `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`)))
                        JOIN `psa_page_multi_zone` `pmz` on(((`pmz`.`PAGE_ID` = `pv`.`PAGE_ID`)
                            AND (`pmz`.`LANGUE_ID` = `pv`.`LANGUE_ID`)
                            AND (`pmz`.`PAGE_VERSION` = `pv`.`PAGE_VERSION`)
                            AND (`tpa`.`AREA_ID` = `pmz`.`AREA_ID`))))
                        JOIN `psa_zone` `z` on (`z`.`ZONE_ID` = `pmz`.`ZONE_ID` )
                        JOIN `psa_page` `p` on (`p`.`PAGE_ID` = `pv`.`PAGE_ID` )
                        JOIN `psa_zone_template` `zt` on (`zt`.`AREA_ID` = `a`.`AREA_ID` and `z`.`ZONE_ID`= `zt`.`ZONE_ID` AND `zt`.`TEMPLATE_PAGE_ID`=`pv`.`TEMPLATE_PAGE_ID`)
                WHERE (`a`.`AREA_DROPPABLE` = 1)
            )"
        );

    }
}
