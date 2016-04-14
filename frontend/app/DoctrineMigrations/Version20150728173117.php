<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150728173117 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_CTA_DESKTOP", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_ADD_CTA_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_LINK_ZONE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_ADD_CTA_LIENS", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_ZONE_CTA_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_LABEL_CTA", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_CTA_MOBILE", NULL, 2, NULL, NULL, 1, NULL)
            '
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_CTA_DESKTOP", 1, 1, "CTA Desktop"),
            ("NDP_ADD_CTA_MOBILE", 1, 1, "Ajouter un CTA mobile"),
            ("NDP_LINK_ZONE", 1, 1, "Zone liens"),
            ("NDP_ADD_CTA_LIENS", 1, 1, "Ajouter un CTA / Lien"),
            ("NDP_ZONE_CTA_MOBILE", 1, 1, "Zone CTA mobile"),
            ("NDP_LABEL_CTA", 1, 1, "Libellé du CTA"),
            ("NDP_CTA_MOBILE", 1, 1, "CTA mobile")
            '
        );

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
                    "NDP_CTA_DESKTOP",
                    "NDP_ADD_CTA_MOBILE",
                    "NDP_LINK_ZONE",
                    "NDP_ADD_CTA_LIENS",
                    "NDP_ZONE_CTA_MOBILE",
                    "NDP_LABEL_CTA",
                    "NDP_CTA_MOBILE"
                )'
            );
        }
    }
}

