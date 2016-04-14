<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150512164130 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_MSG_PARAM_FILTERS', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_FILTER_CATEGORY', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SHOW_DESKTOP_FILTERS', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CATEGORIES_TO_SHOW', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_CATEGORY_ORDER', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEFAULT_MOBILE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_OTHER_FILTERS', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_FILTERS_TO_SHOW', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_CTA_HEAD', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_BY_CATEGORIES', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_BY_MODELES', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_CTA_GESTIONAIRE_VEHICULE', NULL, 2, NULL, NULL, 1, NULL)

                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_MSG_PARAM_FILTERS', 1, 1, 'Le paramétrage des filtres (pas des jauges, etc.) s’effectue dans le gestionnaire de véhicules.'),
                ('NDP_FILTER_CATEGORY', 1, 1, 'Filtre catégories de véhicules'),
                ('NDP_SHOW_DESKTOP_FILTERS', 1, 1, 'Affichage du filtre sur desktop'),
                ('NDP_CATEGORIES_TO_SHOW', 1, 1, 'Catégories à afficher'),
                ('NDP_MSG_CATEGORY_ORDER', 1, 1, 'L’ordonnancement des catégories s’effectue dans le gestionnaire de véhicules.'),
                ('NDP_DEFAULT_MOBILE', 1, 1, 'Affichage par défaut sur le mobile'),
                ('NDP_OTHER_FILTERS', 1, 1, 'Autres filtres'),
                ('NDP_FILTERS_TO_SHOW', 1, 1, 'Filtres à afficher'),
                ('NDP_MSG_CTA_HEAD', 1, 1, 'Calque CTA des vignettes véhicules'),
                ('NDP_BY_CATEGORIES', 1, 1, 'Par catégories'),
                ('NDP_BY_MODELES', 1, 1, 'Par modèles'),
                ('NDP_MSG_CTA_GESTIONAIRE_VEHICULE', 1, 1, 'Les CTA affichés sont administrés dans le gestionnaire de véhicules.')

                ");
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
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_MSG_PARAM_FILTERS',
                 'NDP_FILTER_CATEGORY',
                 'NDP_SHOW_DESKTOP_FILTERS',
                 'NDP_CATEGORIES_TO_SHOW',
                 'NDP_MSG_CATEGORY_ORDER',
                 'NDP_DEFAULT_MOBILE',
                 'NDP_OTHER_FILTERS',
                 'NDP_FILTERS_TO_SHOW',
                 'NDP_MSG_CTA_HEAD',
                 'NDP_BY_CATEGORIES',
                 'NDP_BY_MODELES',
                 'NDP_MSG_CTA_GESTIONAIRE_VEHICULE'

                 )
                "
                );
        }
    }
}
