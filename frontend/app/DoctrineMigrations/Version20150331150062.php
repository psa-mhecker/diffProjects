<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150331150062 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // pn3
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_LIST_TOGGLE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SELECTED_ZONE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MORE_THAN", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_PRECO_UPPERCASE_TITLE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_NOMBRE_TRANCHE", NULL, 2, NULL, NULL, 1, NULL),
                ("OPEN", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_LIST_TOGGLE", 1, 1, "un toggle"),
                ("NDP_SELECTED_ZONE", 1, 1, " tranches par toggle"),
                ("NDP_MORE_THAN", 1, 1, "La limite est de "),
                ("NDP_MSG_PRECO_UPPERCASE_TITLE", 1, 1, "Préconisation saisir le libellé en majuscule"),
                ("NDP_NOMBRE_TRANCHE", 1, 1, "Nombre de tranche"),
                ("OPEN", 1, 1, "Ouvert")
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
             "NDP_LIST_TOGGLE","NDP_SELECTED_ZONE","NDP_MORE_THAN","NDP_MSG_PRECO_UPPERCASE_TITLE",
             "NDP_NOMBRE_TRANCHE","NDP_PN3_TOGGLE_ACCORDEON","OPEN"
             )
        ');
        }


    }
}
