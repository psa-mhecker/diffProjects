<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150310143742 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("CREATE VIEW psa_page_areas as (select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`tpa`.`AREA_ID`) AS `AREA_UID`,`pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,`tpa`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER` from ((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`))) join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`))) where isnull(`a`.`AREA_DROPPABLE`)) union all (select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`) AS `AREA_UID`,`pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,`a`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER` from ((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`))) join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`))) where (`a`.`AREA_DROPPABLE` = 1))");
        $this->addSql("CREATE VIEW `psa_page_areas_blocks` AS (select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`) AS `AREA_UID`,concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `BLOCK_UID`,`pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,`zt`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`zt`.`ZONE_ID` AS `ZONE_ID`,`zt`.`ZONE_TEMPLATE_ID` AS `BLOCK_PAGE_DATA_UID`,`tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,`zt`.`ZONE_TEMPLATE_ORDER` AS `ZONE_ORDER` from (((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`))) join `psa_zone_template` `zt` on(((`tpa`.`TEMPLATE_PAGE_ID` = `zt`.`TEMPLATE_PAGE_ID`) and (`tpa`.`AREA_ID` = `zt`.`AREA_ID`)))) join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`))) where isnull(`a`.`AREA_DROPPABLE`)) union all (select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`) AS `AREA_UID`,concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`ZONE_ORDER`) AS `BLOCK_UID`,`pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,`a`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`pmz`.`ZONE_ID` AS `ZONE_ID`,NULL AS `BLOCK_PAGE_DATA_UID`,`tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,`pmz`.`ZONE_ORDER` AS `ZONE_ORDER` from (((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`))) join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`))) join `psa_page_multi_zone` `pmz` on(((`pmz`.`PAGE_ID` = `pv`.`PAGE_ID`) and (`pmz`.`LANGUE_ID` = `pv`.`LANGUE_ID`) and (`pmz`.`PAGE_VERSION` = `pv`.`PAGE_VERSION`) and (`tpa`.`AREA_ID` = `pmz`.`AREA_ID`)))) where (`a`.`AREA_DROPPABLE` = 1));");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP VIEW `psa_page_areas`;');
        $this->addSql('DROP VIEW `psa_page_areas_blocks`;');

    }
}
