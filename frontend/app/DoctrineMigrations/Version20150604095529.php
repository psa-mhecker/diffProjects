<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150604095529 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_SEARCH_FILTER", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_BUSINESS_FOR_SALE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_AVAILABLE_LOCATION", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_GROUPING", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_AUTOCOMPLETE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_NB_MAX_BUSINESS", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_RADIUS", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_HOME_TEXT", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_LINK_LEARN_MORE", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_SEARCH_FILTER", 1, 1, "Filtres de recherche"),
            ("NDP_BUSINESS_FOR_SALE", 1, 1, "Affaire à vendre"),
            ("NDP_AVAILABLE_LOCATION", 1, 1, "Implantation disponible"),
            ("NDP_GROUPING", 1, 1, "Regroupement"),
            ("NDP_AUTOCOMPLETE", 1, 1, "Autocomplétion"),
            ("NDP_NB_MAX_BUSINESS", 1, 1, "Nombre maximum d’affaires"),
            ("NDP_RADIUS", 1, 1, "Rayon"),
            ("NDP_HOME_TEXT", 1, 1, "Texte d’accueil"),
            ("NDP_LINK_LEARN_MORE", 1, 1, "Lien « En savoir plus »"),
            ("NDP_SEARCH_FILTER", 1, 2, "Filtres de recherche"),
            ("NDP_BUSINESS_FOR_SALE", 1, 2, "Affaire à vendre"),
            ("NDP_AVAILABLE_LOCATION", 1, 2, "Implantation disponible"),
            ("NDP_GROUPING", 1, 2, "Regroupement"),
            ("NDP_AUTOCOMPLETE", 1, 2, "Autocomplétion"),
            ("NDP_NB_MAX_BUSINESS", 1, 2, "Nombre maximum d’affaires"),
            ("NDP_RADIUS", 1, 2, "Rayon"),
            ("NDP_HOME_TEXT", 1, 2, "Texte d’accueil"),
            ("NDP_LINK_LEARN_MORE", 1, 2, "Lien « En savoir plus »")');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                "NDP_SEARCH_FILTER",
                "NDP_BUSINESS_FOR_SALE",
                "NDP_AVAILABLE_LOCATION",
                "NDP_GROUPING",
                "NDP_AUTOCOMPLETE",
                "NDP_NB_MAX_BUSINESS",
                "NDP_RADIUS",
                "NDP_HOME_TEXT",
                "NDP_LINK_LEARN_MORE"
                 )'
            );
        }
    }
}
