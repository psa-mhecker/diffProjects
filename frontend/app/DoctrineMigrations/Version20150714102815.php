<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150714102815 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_PF23_NOTICE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF23_COMPARER_CES_MODELES", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF23_DECOUVRIR", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF23_CONFIGURER", NULL, 2, NULL, NULL, NULL, 1)
        ');

        $this->addSql('INSERT INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
            ("NDP_PF23_NOTICE", 1, "Modèles présentés à titre indicatif. Référence tarif (prix TTC) : 14D", ""),
            ("NDP_PF23_COMPARER_CES_MODELES", 1, "Comparer ces modèles", ""),
            ("NDP_PF23_DECOUVRIR", 1, "Découvrir", ""),
            ("NDP_PF23_CONFIGURER", 1, "Configurer", "")
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_PF23_NOTICE",
                    "NDP_PF23_COMPARER_CES_MODELES",
                    "NDP_PF23_DECOUVRIR",
                    "NDP_PF23_CONFIGURER"
                )'
            );
        }
    }
}
