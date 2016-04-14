<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150609161945 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // trad for pf11 BO
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MODE_SEARCH_PDV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MODE_PROMO_APV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MODE_MANAGEMENT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_FILTER_RADIUS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_FILTER_PDV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_DISPLAY_MAP', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_NB_MAX_PDV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_NB_PDV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_NB_DVN', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_DISPLAY_PHONE_BUTTON', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_FILTER_PDV_LABEL', NULL, 2, NULL, NULL, 1, NULL)
            ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_MODE_SEARCH_PDV', 1, 1, 'Parcours recherche PDV'),
            ('NDP_MODE_PROMO_APV', 1, 1, 'Parcours promo APV'),
            ('NDP_MODE_MANAGEMENT', 1, 1, 'Mode de gestion'),
            ('NDP_FILTER_RADIUS', 1, 1, 'Filtrer par rayon'),
            ('NDP_FILTER_PDV', 1, 1, 'Filtrer par PDV/DVN'),
            ('NDP_DISPLAY_MAP', 1, 1, 'Affichage de la carte'),
            ('NDP_NB_MAX_PDV', 1, 1, 'Nombre maximum de PDV'),
            ('NDP_NB_PDV', 1, 1, 'Nombre de PDV'),
            ('NDP_NB_DVN', 1, 1, 'Nombre de DVN'),
            ('NDP_DISPLAY_PHONE_BUTTON', 1, 1, 'Activer bouton \'Voir le téléphone\''),
            ('NDP_FILTER_PDV_LABEL', 1, 1, 'Filtre par nom PDV')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN (
            "NDP_MODE_SEARCH_PDV",
            "NDP_MODE_PROMO_APV",
            "NDP_MODE_MANAGEMENT",
            "NDP_FILTER_RADIUS",
            "NDP_FILTER_PDV",
            "NDP_DISPLAY_MAP",
            "NDP_NB_MAX_PDV",
            "NDP_NB_PDV",
            "NDP_NB_DVN",
            "NDP_DISPLAY_PHONE_BUTTON",
            "NDP_FILTER_PDV_LABEL"
            )');
        }
    }
}
