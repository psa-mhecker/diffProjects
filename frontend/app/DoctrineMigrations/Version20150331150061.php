<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150331150061 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // pn7
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_TITRE_DE_LA_PAGE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CLASSIQUE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VISUEL_TEXTE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FORMAT_AFFICHAGE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BLANC", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_GRIS", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FOND_ET_DESCRIPTIF", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TYPE_AFFICHAGE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VIDEO", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_COULEUR_DESCRIPTION", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VISUEL_16_9", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ZONE_DESCRIPTION_CTA", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DESCRIPTION", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_STICKER_LE_TITRE_DE_LA_PAGE", NULL, 2, NULL, NULL, 1, NULL),
                ("DISABLED", NULL, 2, NULL, NULL, 1, NULL),
                ("ENABLED", NULL, 2, NULL, NULL, 1, NULL),
                ("PAGE_ZONE_CTA_STATUS", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_TITRE_DE_LA_PAGE", 1, 1, "Titre de la page"),
                ("NDP_CLASSIQUE", 1, 1, "Classique"),
                ("NDP_VISUEL_TEXTE", 1, 1, "Visuel + texte"),
                ("NDP_FORMAT_AFFICHAGE", 1, 1, "Format d\'affichage"),
                ("NDP_BLANC", 1, 1, "Blanc"),
                ("NDP_GRIS", 1, 1, "Gris"),
                ("NDP_FOND_ET_DESCRIPTIF", 1, 1, "Couleur de fond entête et descriptif"),
                ("NDP_TYPE_AFFICHAGE", 1, 1, "Type d\'afichage"),
                ("NDP_VIDEO", 1, 1, "Video"),
                ("NDP_VISUEL_16_9", 1, 1, "Video 16/9"),
                ("NDP_COULEUR_DESCRIPTION", 1, 1, "Couleur de la description"),
                ("NDP_ZONE_DESCRIPTION_CTA", 1, 1, "Zone description + CTA"),
                ("NDP_DESCRIPTION", 1, 1, "Description"),
                ("NDP_STICKER_LE_TITRE_DE_LA_PAGE", 1, 1, "Sticker le titre de la page"),
                ("PAGE_ZONE_CTA_STATUS", 1, 1, "CTA"),
                ("ENABLED", 1, 1, "Activer"),
                ("DISABLED", 1, 1, "Désactiver")
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
             "NDP_TITRE_DE_LA_PAGE",
             "NDP_CLASSIQUE",
             "NDP_VISUEL_TEXTE",
             "NDP_FORMAT_AFFICHAGE",
             "NDP_BLANC",
             "NDP_GRIS",
             "NDP_FOND_ET_DESCRIPTIF",
             "NDP_TYPE_AFFICHAGE",
             "NDP_VIDEO",
             "NDP_VISUEL_16_9",
             "NDP_COULEUR_DESCRIPTION",
             "NDP_ZONE_DESCRIPTION_CTA",
             "NDP_PN7_ENTETE",
             "NDP_DESCRIPTION",
             "NDP_STICKER_LE_TITRE_DE_LA_PAGE",
             "PAGE_ZONE_CTA_STATUS",
             "PAGE_ZONE_CTA_STATUS",
             "ENABLED",
             "DISABLED"
             )
        ');
        }

    }
}
