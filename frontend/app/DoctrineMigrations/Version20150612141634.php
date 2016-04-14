<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150612141634 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
        ("NDP_PF44_INDIQUEZ_UNE_VILLE_OU_CODE_POSTAL", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_PF44_AUTOUR_DE_MOI", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_PF44_VOIR_LA_FICHE_DETAILLEE", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_PF44_PROBLEME_DE_CHARGEMENT", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_PF44_RESULTATS_TROUVES", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_PF44_AUCUN_RESULTAT", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_PF44_GOOGLE_MAP", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_PF44_CONTACTEZ", NULL, 2, NULL, NULL, NULL, 1)
        ');

        $this->addSql('INSERT INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
        ("NDP_PF44_INDIQUEZ_UNE_VILLE_OU_CODE_POSTAL", 1, "Indiquez une ville ou un code postal", ""),
        ("NDP_PF44_AUTOUR_DE_MOI", 1, "Autour de moi...", ""),
        ("NDP_PF44_VOIR_LA_FICHE_DETAILLEE", 1, "Voir la fiche détaillée", ""),
        ("NDP_PF44_PROBLEME_DE_CHARGEMENT", 1, "Nous rencontrons un problème de chargement, Veuillez réessayer plus tard. Si le problème persiste, n'."'".'hésitez pas à nous contacter", ""),
        ("NDP_PF44_RESULTATS_TROUVES", 1, "résultats trouvés", ""),
        ("NDP_PF44_AUCUN_RESULTAT", 1, "Aucun résultat ne correspond à votre recherche", ""),
        ("NDP_PF44_GOOGLE_MAP", 1, "Google map", ""),
        ("NDP_PF44_CONTACTEZ", 1, "Contactez", "")
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                 "NDP_PF44_INDIQUEZ_UNE_VILLE_OU_CODE_POSTAL",
                 "NDP_PF44_AUTOUR_DE_MOI",
                 "NDP_PF44_VOIR_LA_FICHE_DETAILLEE",
                 "NDP_PF44_PROBLEME_DE_CHARGEMENT",
                 "NDP_PF44_RESULTATS_TROUVES",
                 "NDP_PF44_AUCUN_RESULTAT",
                 "NDP_PF44_GOOGLE_MAP",
                 "NDP_PF44_CONTACTEZ"
                 )
                '
            );
        }
    }
}
