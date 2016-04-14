<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150409174005 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
            ("NDP_URL_CTA_AND_VISUEL", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_COLONNE4", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_A_LA_SUITE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_POSITION", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MIN_ITERATION", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_CHAR_MAX", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_SLIDE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_PC59_CTA", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_FORMAT_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MINIATURE_TEXT", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MINIATURE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_ACTUALISER", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_AFFICHAGE_COLONNE", NULL, 2, NULL, NULL, 1, NULL)
            ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
            ("NDP_URL_CTA_AND_VISUEL", 1, 1, "URL du CTA + Visuel"),
            ("NDP_MSG_COLONNE4", 1, 1, "Les valeurs de cette colonne sont remontées automatiquement selon les pages visitées par l’internaute. Dans le cas où l’internaute arrive pour la première fois sur le site, les CTA des master page de niveau 1 s’affichent."),
            ("NDP_A_LA_SUITE", 1, 1, "À la suite"),
            ("NDP_POSITION", 1, 1, "Position"),
            ("NDP_MIN_ITERATION", 1, 1, "Attention, il est au minimum requis : "),
            ("NDP_CHAR_MAX", 1, 1, "car. max"),
            ("NDP_SLIDE", 1, 1, "Slide"),
            ("NDP_MSG_PC59_CTA", 1, 1, "Veuillez vérifier que les CTA importés du référentiel comportent bien un visuel. Si un visuel est absent dans 1 des 4 CTA alors la tranche s’affichera sans pictogramme."),
            ("NDP_FORMAT_MOBILE", 1, 1, "Format mobile"),
            ("NDP_MINIATURE_TEXT", 1, 1, "Texte Miniature"),
            ("NDP_MINIATURE", 1, 1, "Miniature"),
            ("NDP_ACTUALISER", 1, 1, "Actualiser"),
            ("NDP_AFFICHAGE_COLONNE", 1, 1, "Affichage des colonnes")
        ');

        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Visuel 16/9 Web' WHERE LABEL_ID ='NDP_VISUEL_16_9_WEB' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Visuel 16/9 Mobile' WHERE LABEL_ID ='NDP_VISUEL_16_9_MOB' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Engagement (droite)' WHERE LABEL_ID ='NDP_ENGAGEMENT_DROITE' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Engagement (gauche)' WHERE LABEL_ID ='NDP_ENGAGEMENT_GAUCHE' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Engagement (milieu)' WHERE LABEL_ID ='NDP_ENGAGEMENT_MILIEU' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Visuel 16/9' WHERE LABEL_ID ='NDP_VISUEL_16_9' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Format Attendu : ' WHERE LABEL_ID ='FORMAT_ATTENDU' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Affichage web' WHERE LABEL_ID ='AFFICHAGE_WEB' AND SITE_ID = 1 AND LANGUE_ID = 1");

        $this->addSql("UPDATE psa_label_langue_site SET LABEL_ID = 'NDP_MSG_ENGAGEMENT' WHERE LABEL_ID ='NDP_INFORMATION_ENGAGEMENT'");
        $this->addSql("UPDATE psa_label SET LABEL_ID = 'NDP_MSG_ENGAGEMENT' WHERE LABEL_ID ='NDP_INFORMATION_ENGAGEMENT'");

        // FO
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
            ("NDP_YOUR_EMAIL", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_FIELD_REQUIRED", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_OK", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CONTACT_CENTER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CHOOSE_LANGUAGE", NULL, 2, NULL, NULL, NULL, 1)
            ');
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_YOUR_EMAIL", 1, "Votre email", ""),
            ("NDP_FIELD_REQUIRED", 1, "Ce champs est requis", ""),
            ("NDP_OK", 1, "ok", ""),
            ("NDP_CONTACT_CENTER", 1, "centre de contact", ""),
            ("NDP_CHOOSE_LANGUAGE", 1, "Sélectionnez votre langue", ""),
            ("NDP_CHOOSE_LANGUAGE", 2, "Choose your language", "")
        ');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
