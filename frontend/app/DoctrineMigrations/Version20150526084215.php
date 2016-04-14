<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150526084215 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ("NDP_MSG_ACTIVATE_COMPARISON_TABLE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FINISHING", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FINISHING_AND_ENGINES", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BY_FINISHING", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BY_ENGINES", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LABEL_COMPARISON_TABLE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DISPLAY_FIRST", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ENGINES", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ("NDP_MSG_ACTIVATE_COMPARISON_TABLE", 1, 1, "Il est nécessaire d’activer le tableau comparatif de versions dans le Gestionnaire de véhicules pour l’activer sur cette tranche."),
                ("NDP_FINISHING", 1, 1, "Finitions"),
                ("NDP_FINISHING_AND_ENGINES", 1, 1, "Finitions & Motorisations"),
                ("NDP_BY_FINISHING", 1, 1, "Par finitions"),
                ("NDP_BY_ENGINES", 1, 1, "Par motorisations"),
                ("NDP_LABEL_COMPARISON_TABLE", 1, 1, "Tableau comparatif des versions"),
                ("NDP_DISPLAY_FIRST", 1, 1, "Afficher en premier"),
                ("NDP_ENGINES", 1, 1, "Motorisations")
                ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                 "NDP_MSG_ACTIVATE_COMPARISON_TABLE",
                 "NDP_FINISHING",
                 "NDP_FINISHING_AND_ENGINES",
                 "NDP_BY_FINISHING",
                 "NDP_BY_ENGINES",
                 "NDP_LABEL_COMPARISON_TABLE",
                 "NDP_DISPLAY_FIRST",
                 "NDP_ENGINES"
                 )
                '
            );
        }
    }
}
