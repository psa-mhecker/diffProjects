<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150622161341 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE psa_page_datalayer (SITE_ID int(11) NOT NULL, PAGE_ID int(11) NOT NULL, LANGUE_ID int(11) NOT NULL, DATALAYER text, PRIMARY KEY (SITE_ID, PAGE_ID, LANGUE_ID)) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->addSql('CREATE TABLE psa_block_datalayer (BLOCK_PERMANENT_ID varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL, DATALAYER text CHARACTER SET utf8 COLLATE utf8_swedish_ci, PRIMARY KEY (BLOCK_PERMANENT_ID)) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("FORM_MSG_FORMAT", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("FORM_MSG_FORMAT", 1, 1, "Le format n\'est pas valide")'
        );

        $this->addSql('ALTER TABLE psa_page_multi_zone ADD UID VARCHAR(255) NULL FIRST');
        $this->addSql('DROP view psa_page_areas_blocks');

        $this->addSql("CREATE VIEW `psa_page_areas_blocks` AS (
                select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`) AS `AREA_UID`,
                    concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `BLOCK_UID`,
                    concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`, 'notmovable') AS `PERMANENT_ID`,
                    `pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,
                    `zt`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`zt`.`ZONE_ID` AS `ZONE_ID`,`zt`.`ZONE_TEMPLATE_ID` AS `BLOCK_PAGE_DATA_UID`, NULL as `MULTI_ZONE_UID`,
                    `tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,`zt`.`ZONE_TEMPLATE_ORDER` AS `ZONE_ORDER`
                from (((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`)))
                join `psa_zone_template` `zt` on(((`tpa`.`TEMPLATE_PAGE_ID` = `zt`.`TEMPLATE_PAGE_ID`) and (`tpa`.`AREA_ID` = `zt`.`AREA_ID`))))
                join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`)))
                where isnull(`a`.`AREA_DROPPABLE`)
            ) union all (
                select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`) AS `AREA_UID`,
                    concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`ZONE_ORDER`) AS `BLOCK_UID`,
                    concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`, `pmz`.`UID`) AS `PERMANENT_ID`,
                    `pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,
                    `a`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`pmz`.`ZONE_ID` AS `ZONE_ID`,NULL AS `BLOCK_PAGE_DATA_UID`, `pmz`.`UID` as `MULTI_ZONE_UID`,
                    `tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,`pmz`.`ZONE_ORDER` AS `ZONE_ORDER`
                from (((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`)))
                join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`)))
                join `psa_page_multi_zone` `pmz` on(((`pmz`.`PAGE_ID` = `pv`.`PAGE_ID`) and (`pmz`.`LANGUE_ID` = `pv`.`LANGUE_ID`) and (`pmz`.`PAGE_VERSION` = `pv`.`PAGE_VERSION`) and (`tpa`.`AREA_ID` = `pmz`.`AREA_ID`))))
                where (`a`.`AREA_DROPPABLE` = 1))
        ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE psa_page_datalayer');
        $this->addSql('DROP TABLE psa_block_datalayer');
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
              'DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                    "FORM_MSG_FORMAT"
                )'
            );
        }

        $this->addSql('ALTER TABLE psa_page_multi_zone DROP UID');
        $this->addSql('DROP view psa_page_areas_blocks');

        $this->addSql("CREATE  VIEW `psa_page_areas_blocks` AS (
            select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`) AS `AREA_UID`,
            concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `BLOCK_UID`,
            `pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,
            `zt`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`zt`.`ZONE_ID` AS `ZONE_ID`,`zt`.`ZONE_TEMPLATE_ID` AS `BLOCK_PAGE_DATA_UID`,
            `tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,`zt`.`ZONE_TEMPLATE_ORDER` AS `ZONE_ORDER`
            from (((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`)))
            join `psa_zone_template` `zt` on(((`tpa`.`TEMPLATE_PAGE_ID` = `zt`.`TEMPLATE_PAGE_ID`) and (`tpa`.`AREA_ID` = `zt`.`AREA_ID`))))
            join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`)))
            where isnull(`a`.`AREA_DROPPABLE`)
            ) union all (
            select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`) AS `AREA_UID`,
            concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`ZONE_ORDER`) AS `BLOCK_UID`,
            `pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,
            `a`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`pmz`.`ZONE_ID` AS `ZONE_ID`,NULL AS `BLOCK_PAGE_DATA_UID`,
            `tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,`pmz`.`ZONE_ORDER` AS `ZONE_ORDER`
            from (((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`)))
            join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`)))
            join `psa_page_multi_zone` `pmz` on(((`pmz`.`PAGE_ID` = `pv`.`PAGE_ID`) and (`pmz`.`LANGUE_ID` = `pv`.`LANGUE_ID`) and (`pmz`.`PAGE_VERSION` = `pv`.`PAGE_VERSION`) and (`tpa`.`AREA_ID` = `pmz`.`AREA_ID`))))
            where (`a`.`AREA_DROPPABLE` = 1))
        ");
    }
}
