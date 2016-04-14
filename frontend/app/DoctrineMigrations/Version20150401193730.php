<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401193730 extends AbstractMigration
{
      /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // pn2
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_TAB_TITRE2", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TAB_TITRE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TAB", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PN2_ONGLET", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_NB_TAB_TOOLTIP", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_NB_ZONE", NULL, 2, NULL, NULL, 1, NULL)

                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_TAB_TITRE2", 1, 1, "Libellé onglet (Mobile)"),
                ("NDP_TAB_TITRE", 1, 1, "Surcharge libellé onglet (Desktop)"),
                ("NDP_TAB", 1, 1, "Onglet "),
                ("NDP_PN2_ONGLET", 1, 1, "Onglets"),
                ("NDP_NB_TAB_TOOLTIP", 1, 1, "Prend en compte les premières tranches glissées/déposées sous cette tranche onglet."),
                ("NDP_NB_ZONE", 1, 1, "Nombre de tranche correspondant a l\'onglet ")

        ');
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
                "NDP_TAB_TITRE2",
                "NDP_TAB_TITRE",
                "NDP_TAB",
                "NDP_PN2_ONGLET",
                "NDP_NB_TAB_TOOLTIP",
                "NDP_NB_ZONE"
                )
            ');
        }
    }
}
