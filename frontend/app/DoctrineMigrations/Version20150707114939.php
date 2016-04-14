<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150707114939 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (838, 1, 'NDP_PF23_RANGEBAR', 0, NULL, 'Cms_Page_Ndp_Pf23Rangebar', NULL, 0, 0, 0, NULL, NULL, 28, 0, '')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES "
            ."(4496,'NDP_PF23_RANGEBAR',290,150,838,1,NULL, NULL,NULL,30)"
            .";"
        );
         $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_RANGEBAR_CARS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_MAX_RANGERBAR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_FIRST_LINK_HELP', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_LIST_CAR_RANGEBAR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_BEHAVIOUR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_FROM_RANGE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_FROM_CAR_HANDLER', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_VISUAL_FROM_3D', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PF23_RANGEBAR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_PRICE_FROM_GENERAL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_LIGHT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_FULL_EXPAND', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_LEGAL_FROM_TRANSLATE', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_RANGEBAR_CARS', 1, 1, 'Modèles de la Rangebar'),
            ('NDP_MSG_MAX_RANGERBAR', 1, 1, 'L\'utilisateur doit sélectionner au moins 1 modèle et jusqu\'à 13 modèles maximum.'),
            ('NDP_LIGHT', 1, 1, 'Light'),
            ('NDP_FULL_EXPAND', 1, 1, 'Full (Avec expand)'),
            ('NDP_PF23_RANGEBAR', 1, 1, 'PF23 Car range bar_ specific HP_only desktop'),
            ('NDP_MSG_FIRST_LINK_HELP', 1, 1, '1er lien (Aide au choix)'),
            ('NDP_MSG_LIST_CAR_RANGEBAR', 1, 1, 'Liste des modèles de la Range bar'),
            ('NDP_BEHAVIOUR', 1, 1, 'Comportement'),
            ('NDP_MSG_FROM_RANGE', 1, 1, '- Les regroupements de silhouettes des modèles sont remontés de la Gestion de la gamme.'),
            ('NDP_MSG_FROM_CAR_HANDLER', 1, 1, '- La phrase d’accroche des modèles, la catégorie ainsi que les CTA des modèles/regroupements de silhouettes sont administrés dans le Gestionnaire de véhicules.'),
            ('NDP_MSG_VISUAL_FROM_3D', 1, 1, '- Les visuels véhicules sont remontés automatiquement de la Baie Visuels 3D.'),
            ('NDP_MSG_PRICE_FROM_GENERAL', 1, 1, '- L’affichage par défaut des prix mensuels ou comptants, des avis clients Reevoo sont administrés dans les paramètres généraux.'),
            ('NDP_MSG_LEGAL_FROM_TRANSLATE', 1, 1, '- Les mentions légales générales sont gérées dans le référentiel de traductions.')
           ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID = 4496");
        $this->addSql("DELETE FROM `psa_zone` WHERE ZONE_ID = 838");
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_RANGEBAR_CARS",
                    "NDP_MSG_FIRST_LINK_HELP",
                    "NDP_MSG_LIST_CAR_RANGEBAR",
                    "NDP_BEHAVIOUR",
                    "NDP_MSG_FROM_RANGE",
                    "NDP_MSG_VISUAL_FROM_3D",
                    "NDP_MSG_PRICE_FROM_GENERAL",
                    "NDP_FULL_EXPAND",
                    "NDP_LIGHT",
                    "NDP_PF23_RANGEBAR",
                    "NDP_MSG_MAX_RANGERBAR",
                    "NDP_MSG_FROM_CAR_HANDLER",
                    "NDP_MSG_LEGAL_FROM_TRANSLATE"
                )'
            );
        }

    }
}
