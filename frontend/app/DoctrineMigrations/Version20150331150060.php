<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150331150060 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // pt19
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_TITRE_DE_LA_TRANCHE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ENGAGEMENT_DROITE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ENGAGEMENT_GAUCHE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ENGAGEMENT_MILIEU", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_INFORMATION_ENGAGEMENT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_1SEULCONTENU", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_TITRE_DE_LA_TRANCHE", 1, 1, "Titre de la tranche"),
                ("NDP_ENGAGEMENT_DROITE", 1, 1, "Engagement(droite)"),
                ("NDP_ENGAGEMENT_GAUCHE", 1, 1, "Engagement(gauche)"),
                ("NDP_ENGAGEMENT_MILIEU", 1, 1, "Engagement(milieu)"),
                ("NDP_INFORMATION_ENGAGEMENT", 1, 1, "Vous pouvez  Modifier/Créer un engagement dans le référentiel contenu engagement situé dans la rubrique XXX>XXX> référentiel contenu engagement. Si un engagements ne comporte pas de visuel alors les 3 engagement sélectionnées d\'afficheront sans visuel."),
                ("NDP_MSG_1SEULCONTENU", 1, 1, "Vous ne pouvez pas utiliser 2 fois le même contenu")
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
             "NDP_TITRE_DE_LA_TRANCHE",
             "NDP_ENGAGEMENT_DROITE",
             "NDP_ENGAGEMENT_GAUCHE",
             "NDP_ENGAGEMENT_MILIEU",
             "NDP_PT19_ENGAGEMENTS",
             "NDP_INFORMATION_ENGAGEMENT",
             "NDP_MSG_1SEULCONTENU"
             )
        ');
        }
    }
}
