<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150331150057 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // generique
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("ADD_FORM_CTA", NULL, 2, NULL, NULL, 1, NULL),
                ("LABEL", NULL, 2, NULL, NULL, 1, NULL),
                ("TARGET", NULL, 2, NULL, NULL, 1, NULL),
                ("SELECT_CTA", NULL, 2, NULL, NULL, 1, NULL),
                ("CTA_ACTION", NULL, 2, NULL, NULL, 1, NULL),
                ("PICTO", NULL, 2, NULL, NULL, 1, NULL),
                ("STYLE", NULL, 2, NULL, NULL, 1, NULL),
                ("CTA_NEW", NULL, 2, NULL, NULL, 1, NULL),
                ("CTA_REF", NULL, 2, NULL, NULL, 1, NULL)

                ');

        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("ADD_FORM_CTA", 1, 1, " un CTA"),
                ("LABEL", 1, 1, "Libellé"),
                ("PICTO", 1, 1, "Picto"),
                ("TARGET", 1, 1, "Target"),
                ("SELECT_CTA", 1, 1, "Selectionner un CTA"),
                ("CTA_ACTION", 1, 1, "Target"),
                ("STYLE", 1, 1, "Style"),
                ("CTA_NEW", 1, 1, "Nouveau CTA"),
                ("CTA_REF", 1, 1, "Choisir  depuis le référentiel")

                ');
        // pt3
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_MSG_PICTO_COLONNE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_PICTO_MANQUANT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TITRE_EXPAND", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_MSG_PICTO_COLONNE", 1, 1, "Les pictos doivent être renseignés dans chaque colonne pour que ceux ci apparaissent en FO"),
                ("NDP_MSG_PICTO_MANQUANT", 1, 1, "Si un picto est manquant aucun des pictos ne s\'affichera"),
                ("NDP_TITRE_EXPAND", 1, 1, "Titre expand")

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
             "ADD_FORM_CTA",
             "PICTO",
             "TARGET",
             "SELECT_CTA",
             "CTA_ACTION",
             "STYLE",
             "CTA_NEW",
             "CTA_REF",
             "NDP_MSG_PICTO_COLONNE",
             "NDP_PT3_JE_VEUX",
             "NDP_PT3",
             "NDP_MSG_PICTO_MANQUANT",
             "NDP_TITRE_EXPAND",
             "LABEL"
             )
        ');
        }
    }
}
