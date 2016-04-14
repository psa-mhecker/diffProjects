<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150806165451 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {


        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_APPLICATION_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VISUEL_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_URL_AND_BADGES", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BADGE_GOOGLE_PLAY", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BADGE_APPLE_STORE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BADGE_WINDOWS", NULL, 2, NULL, NULL, 1, NULL)

                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_APPLICATION_MOBILE", 1, 1, "Applications mobile"),
                ("NDP_VISUEL_MOBILE", 1, 1, "Visuel mobileVisuel mobile"),
                ("NDP_URL_AND_BADGES", 1, 1, "URL et badges"),
                ("NDP_BADGE_GOOGLE_PLAY", 1, 1, "Badge Google store"),
                ("NDP_BADGE_APPLE_STORE", 1, 1, "Badge Apple Store"),
                ("NDP_BADGE_WINDOWS", 1, 1, "Badge Apple Store")

        ');

        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_WINDOWS", NULL, 2, NULL, NULL, NULL, 1)
            '
        );

        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES

            ("NDP_WINDOWS", 1, "Windows Store", "")
            '
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
             "NDP_APPLICATION_MOBILE",
             "NDP_VISUEL_MOBILE",
             "NDP_URL_AND_BADGES",
             "NDP_BADGE_GOOGLE_PLAY",
             "NDP_BADGE_APPLE_STORE",
             "NDP_BADGE_WINDOWS"
             )
        ');
        }

        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_WINDOWS"
                )'
            );
        }
    }
}
