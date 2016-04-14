<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150807154326 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = 1 WHERE LABEL_ID IN (
                "NDP_NEW",
                "NDP_SPECIAL_OFFER",
                "NDP_SPECIAL_SERIE"
            )'
        );

        $this->addSql(
            'INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_NEW", 1, "Nouveauté", ""),
            ("NDP_SPECIAL_OFFER", 1, "Offre spéciale", ""),
            ("NDP_SPECIAL_SERIE", 1, "Série spéciale", "")
            '
        );


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = NULL WHERE LABEL_ID IN (
                    "NDP_NEW",
                    "NDP_SPECIAL_OFFER",
                    "NDP_SPECIAL_SERIE"
                )'
        );

        $this->addSql(
            'DELETE FROM `psa_label_langue`  WHERE `LABEL_ID` IN
                (
                    "NDP_NEW",
                    "NDP_SPECIAL_OFFER",
                    "NDP_SPECIAL_SERIE"
                )'
        );
    }
}
