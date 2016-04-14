<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150709155709 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_PC60_CONFIGURER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_VOTRE_LOCALITE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_VOTRE_CODE_POSTAL", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_VEUILLEZ_REMPLIR_CE_CHAMPS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_EX_75018", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_VALIDER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_BY", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_REEVOO", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_READ_356_REVIEWS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC60_PARTAGER", NULL, 2, NULL, NULL, NULL, 1)
            '
        );

        $this->addSql('INSERT INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
            ("NDP_PC60_CONFIGURER", 1, "Configurer", ""),
            ("NDP_PC60_VOTRE_LOCALITE", 1, "Votre localité", ""),
            ("NDP_PC60_VOTRE_CODE_POSTAL", 1, "Renseignez votre code postal", ""),
            ("NDP_PC60_VEUILLEZ_REMPLIR_CE_CHAMPS", 1, "Veuillez remplir ce champs.", ""),
            ("NDP_PC60_EX_75018", 1, "Ex : 75018", ""),
            ("NDP_PC60_VALIDER", 1, "valider", ""),
            ("NDP_PC60_BY", 1, "by", ""),
            ("NDP_PC60_REEVOO", 1, "reevoo", ""),
            ("NDP_PC60_READ_356_REVIEWS", 1, "Read 356 reviews", ""),
            ("NDP_PC60_PARTAGER", 1, "Partager", "")
            '
        );
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
                    "NDP_PC60_CONFIGURER",
                    "NDP_PC60_VOTRE_LOCALITE",
                    "NDP_PC60_VOTRE_CODE_POSTAL",
                    "NDP_PC60_VEUILLEZ_REMPLIR_CE_CHAMPS",
                    "NDP_PC60_EX_75018",
                    "NDP_PC60_VALIDER",
                    "NDP_PC60_BY",
                    "NDP_PC60_REEVOO",
                    "NDP_PC60_READ_356_REVIEWS",
                    "NDP_PC60_PARTAGER"
                )'
            );
        }
    }
}
