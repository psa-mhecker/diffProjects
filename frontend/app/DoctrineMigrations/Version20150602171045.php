<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150602171045 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {       
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ("NDP_LABEL_ANNOUNCEMENT_REVEAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LABEL_ANNOUNCEMENT_LAUNCH", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LABEL_MARKETING", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PRESENTATION", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_NON_RENSEIGNE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ANNOUNCEMENT_REVEAL_DATE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ANNOUNCEMENT_LAUNCH_DATE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MARKETING_DATE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_ANNOUNCEMENT_REVEAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_ANNOUNCEMENT_LAUNCH", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_MARKETING", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_AFFICHAGE_DE_LA_ZONE_ANNONCE_REVEAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_ERROR_PUBLICATION_DATE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DISPLAY_HEURE_BEGIN", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_SUPERIEURE_OU_EGALE_A_LA_DATE_DU_JOUR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_INFERIEURE_OU_EGALE_A_LA_DATE_DE_FIN", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_GAUCHE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DROITE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DECOMPTE", NULL, 2, NULL, NULL, 1, NULL)
                
                ');
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ("NDP_LABEL_ANNOUNCEMENT_REVEAL", 1, 1, "1 - Annonce du Reveal"),
                ("NDP_LABEL_ANNOUNCEMENT_LAUNCH", 1, 1, "2 - Reveal / Annonce du lancement commercial"),
                ("NDP_LABEL_MARKETING", 1, 1, "3 - Commercialisation"),
                ("NDP_PRESENTATION", 1, 1, "Présentation"),
                ("NDP_NON_RENSEIGNE", 1, 1, "Non renseigné"),
                ("NDP_ANNOUNCEMENT_REVEAL_DATE", 1, 1, "Date du lancement commercial: [date de fin de publication de la page + heure : minute] "),
                ("NDP_ANNOUNCEMENT_LAUNCH_DATE", 1, 1, "Date du lancement commercial: [date de fin de publication de la page + heure : minute] "),
                ("NDP_MARKETING_DATE", 1, 1, "Date du lancement commercial: [date de fin de publication de la page + heure : minute] "),
                ("NDP_MSG_ANNOUNCEMENT_REVEAL", 1, 1, "L’affichage de cette présentation peut être paramétré jusqu’à la date du Reveal. 
                Dans ce cas, la date de fin de publication de la page doit correspondre à la date du Reveal.
                Une Welcome Page Reveal / Annonce du lancement commercial doit également être programmée pour s’afficher à la fin de publication de la Welcome Page Annonce du Reveal.
                
                Date du Reveal "),
                ("NDP_MSG_ANNOUNCEMENT_LAUNCH", 1, 1, "L’affichage de cette présentation peut être paramétré jusqu’à la date du lancement commercial. 
                Dans ce cas, la date de fin de publication de la page doit correspondre à la date du lancement commercial.
                Une Welcome Page Commercialisation doit également être programmée pour s’afficher à la fin de publication de la Welcome Page Reveal / Annonce du lancement commercial."),
                ("NDP_MSG_MARKETING", 1, 1, "L’affichage de cette présentation peut être paramétré à partir de la date du lancement commercial. Dans ce cas, la date de début de publication de la page doit correspondre à la date du lancement commercial. "),
                ("NDP_AFFICHAGE_DE_LA_ZONE_ANNONCE_REVEAL", 1, 1, "Affichage de la zone d’information"),
                ("NDP_MSG_ERROR_PUBLICATION_DATE", 1, 1, "Veuillez renseigner la date de fin de publication de la page pour activer le décompte. "),
                ("NDP_DISPLAY_HEURE_BEGIN", 1, 1, "Heure début d’affichage"),
                ("NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_SUPERIEURE_OU_EGALE_A_LA_DATE_DU_JOUR", 1, 1, "La date sélectionnée doit être supérieure ou égale  à la date du jour "),
                ("NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_INFERIEURE_OU_EGALE_A_LA_DATE_DE_FIN", 1, 1, "La date sélectionnée doit être inférieure ou égale à la date de fin de publication de la page "),
                ("NDP_GAUCHE", 1, 1, "Gauche"),
                ("NDP_DROITE", 1, 1, "Droite"),
                ("NDP_DECOMPTE", 1, 1, "Décompte")
                ');
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
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                 "NDP_LABEL_ANNOUNCEMENT_REVEAL",
                 "NDP_LABEL_ANNOUNCEMENT_LAUNCH",
                 "NDP_LABEL_MARKETING",
                 "NDP_PRESENTATION",
                 "NDP_NON_RENSEIGNE",
                 "NDP_ANNOUNCEMENT_REVEAL_DATE",
                 "NDP_ANNOUNCEMENT_LAUNCH_DATE",
                 "NDP_MARKETING_DATE",
                 "NDP_MSG_ANNOUNCEMENT_REVEAL",
                 "NDP_MSG_ANNOUNCEMENT_LAUNCH",
                 "NDP_MSG_MARKETING",
                 "NDP_AFFICHAGE_DE_LA_ZONE_ANNONCE_REVEAL",
                 "NDP_MSG_ERROR_PUBLICATION_DATE",
                 "NDP_DISPLAY_HEURE_BEGIN",
                 "NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_SUPERIEURE_OU_EGALE_A_LA_DATE_DU_JOUR",
                 "NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_INFERIEURE_OU_EGALE_A_LA_DATE_DE_FIN",
                 "NDP_GAUCHE",
                 "NDP_DROITE",
                 "NDP_DECOMPTE"
                 )
                '
            );
        }
    }
}
