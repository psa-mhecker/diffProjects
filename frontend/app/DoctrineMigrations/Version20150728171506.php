<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150728171506 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION_CHILD_SUITE", NULL, 2, NULL, NULL, 1, NULL)
            '
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION_CHILD_SUITE", 1, 1, "L’administration s’effectue sur la Welcome page du showroom")
            '
        );
        $this->addSql('UPDATE psa_label_langue_site
                  SET LABEL_TRANSLATE = "Cette tranche est affichée sur toutes les pages du showroom excepté sur la Welcome page."
                  WHERE LABEL_ID = "NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION_CHILD"  AND LANGUE_ID =1');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION_CHILD_SUITE"
                )'
            );
        }
    }
}
