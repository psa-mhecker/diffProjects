<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150629155303 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Rename key from pf44
        $changeKeyTrad = array(
            'NDP_PROBLEME_DE_CHARGEMENT_AJAX' => 'NDP_AJAX_LOADING_ISSUE',
            'NDP_PF44_CONTACTEZ' => 'NDP_PF44_CONTACT',
            'NDP_PF44_AUTOUR_DE_MOI' => 'NDP_AROUND_ME',
            'NDP_PF44_RESULTATS_TROUVES' => 'NDP_RESULTS_FOUND',
            'NDP_PF44_AUCUN_RESULTAT' => 'NDP_NO_RESULT',
            'NDP_PF44_INDIQUEZ_UNE_VILLE_OU_CODE_POSTAL' => 'NDP_INDICATE_CITY_OR_POSTAL_CODE',
            'NDP_PF44_VOIR_LA_FICHE_DETAILLEE' => 'NDP_VIEW_DETAILED_SHEET'
        );
        foreach ($changeKeyTrad as $newKey => $oldKey) {
            $this->addSql('UPDATE psa_label SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
            $this->addSql('UPDATE psa_label_langue SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
        }

        // FO Trad for PF11
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
            ("NDP_TEL", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_INDICATE_POINT_OF_SALE_NAME", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_BY_POINT_OF_SALE_NAME", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_BY_CITY_OR_POSTAL_CODE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_RETURN_TO_POINT_OF_SALE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_CONTACT", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_SERVICES", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_VISIT_WEBSITE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_VCF_CONTACT_SHEET", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PF11_FIND_POINT_OF_SALE", NULL, 2, NULL, NULL, NULL, 1)
            ');
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_TEL", 1, "Tel .", ""),
            ("NDP_PF11_INDICATE_POINT_OF_SALE_NAME", 1, "Indiquez un nom de point de vente", ""),
            ("NDP_PF11_BY_POINT_OF_SALE_NAME", 1, "Par le nom du point de vente", ""),
            ("NDP_PF11_BY_CITY_OR_POSTAL_CODE", 1, "Par ville ou code postal", ""),
            ("NDP_PF11_RETURN_TO_POINT_OF_SALE", 1, "Retour aux points de vente", ""),
            ("NDP_PF11_CONTACT", 1, "contact", ""),
            ("NDP_PF11_SERVICES", 1, "services", ""),
            ("NDP_PF11_VISIT_WEBSITE", 1, "Visitez le site", ""),
            ("NDP_PF11_VCF_CONTACT_SHEET", 1, "Par ville ou code postal", ""),
            ("NDP_PF11_FIND_POINT_OF_SALE", 1, "fiche contact (vcf)", "")
        ');

        // Fix NDP_CLOSE to be in FO trad table
        $this->addSql("DELETE FROM `psa_label_langue_site` WHERE LABEL_ID = 'NDP_CLOSE'");
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_CLOSE", 1, "Fermer", "")
        ');

        // BO trad missing TODO SEARCH_CRITERIA
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_PF11_SEARCH_CRITERIA', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_PF11_SEARCH_CRITERIA', 1, 1, 'Critères de recherche (parcours classique)')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $changeKeyTrad = array(
            'NDP_AJAX_LOADING_ISSUE' => 'NDP_PROBLEME_DE_CHARGEMENT_AJAX',
            'NDP_PF44_CONTACT' => 'NDP_PF44_CONTACTEZ',
            'NDP_AROUND_ME' => 'NDP_PF44_AUTOUR_DE_MOI',
            'NDP_RESULTS_FOUND' => 'NDP_PF44_RESULTATS_TROUVES',
            'NDP_NO_RESULT' => 'NDP_PF44_AUCUN_RESULTAT',
            'NDP_INDICATE_CITY_OR_POSTAL_CODE' => 'NDP_PF44_INDIQUEZ_UNE_VILLE_OU_CODE_POSTAL',
            'NDP_VIEW_DETAILED_SHEET' => 'NDP_PF44_VOIR_LA_FICHE_DETAILLEE'
        );

        foreach ($changeKeyTrad as $newKey => $oldKey) {
            $this->addSql('UPDATE psa_label SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
            $this->addSql('UPDATE psa_label_langue SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
        }

        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                "NDP_TEL",
                "NDP_PF11_INDICATE_POINT_OF_SALE_NAME",
                "NDP_PF11_BY_POINT_OF_SALE_NAME",
                "NDP_PF11_BY_CITY_OR_POSTAL_CODE",
                "NDP_PF11_RETURN_TO_POINT_OF_SALE",
                "NDP_PF11_CONTACT",
                "NDP_PF11_SERVICES",
                "NDP_PF11_VISIT_WEBSITE",
                "NDP_PF11_VCF_CONTACT_SHEET",
                "NDP_PF11_FIND_POINT_OF_SALE"
                 )'
            );
        }

        $this->addSql("DELETE FROM `psa_label_langue` WHERE LABEL_ID = 'NDP_CLOSE'");
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_CLOSE", 1, 1, "Fermer")
        ');

        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
                "NDP_PF11_SEARCH_CRITERIA"
             )
        ');
        }

    }
}
