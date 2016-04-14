<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150427152730 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Titre miniature' WHERE LABEL_ID ='NDP_MINIATURE_TEXT' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('ADD_SLIDE_VISUEL', NULL, 2, NULL, NULL, 1, NULL),
                ('ADD_SLIDE_HTML5', NULL, 2, NULL, NULL, 1, NULL),
                ('ADD_SLIDE_VIDEO', NULL, 2, NULL, NULL, 1, NULL),
                ('ADD_CTA_LISTE_DEROULANTE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ADD_LIEN', NULL, 2, NULL, NULL, 1, NULL),
                ('PDF', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_TAILLE_VISUEL',  NULL, 2, NULL, NULL, 1, NULL),
                ('AFFICHAGE_FICHE_SERVICE_SHOWROOM', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_TITRE_ANCRE',  NULL, 2, NULL, NULL, 1, NULL),
                ('ANCHOR',  NULL, 2, NULL, NULL, 1, NULL),
                ('VISUEL_WEB',  NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_VISUEL',  NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ADD_SLIDE',  NULL, 2, NULL, NULL, 1, NULL),
                ('ALERT_PLAN_MOBILE',  NULL, 2, NULL, NULL, 1, NULL),
                ('ADD_MODELCAR',  NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ADD_TOGGLE',  NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ADD_VERBATIM',  NULL, 2, NULL, NULL, 1, NULL)
                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('ADD_SLIDE_VISUEL', 1, 1, 'Ajouter un visuel'),
            ('ADD_SLIDE_HTML5', 1, 1,  'Ajouter un bloc HTML'),
            ('ADD_SLIDE_VIDEO', 1, 1,  'Ajouter une vidéo'),
            ('ADD_CTA_LISTE_DEROULANTE', 1, 1, 'Ajouter un CTA à la liste déroulante'),
            ('NDP_ADD_LIEN', 1, 1, 'Ajouter un lien'),
            ('PDF', 1, 1, 'PDF'),
            ('NDP_TAILLE_VISUEL', 1, 1, 'A gauche du texte'),
            ('AFFICHAGE_FICHE_SERVICE_SHOWROOM', 1, 1, 'Affichage fiche service showroom'),
            ('NDP_TITRE_ANCRE', 1, 1, 'Titre de l\'ancre'),
            ('NDP_VISUEL', 1, 1, 'Visuel'),
            ('ANCHOR', 1, 1, 'Ancre'),
            ('VISUEL_WEB', 1, 1, 'Visuel Web'),
            ('NDP_ADD_SLIDE', 1, 1, 'Ajouter une slide'),
            ('ALERT_PLAN_MOBILE', 1, 1, 'La page plan du site n\'existe pas sur mobile.'),
            ('ADD_MODELCAR', 1, 1, 'Ajouter un modèle'),
            ('NDP_ADD_TOGGLE', 1, 1, 'Visuel'),
            ('NDP_ADD_VERBATIM', 1, 1, 'Ajouter un verbatim')            
            ");
            $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Colonnes' WHERE LABEL_ID ='NDP_COLONNES' AND SITE_ID = 1 AND LANGUE_ID = 1");
           
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Texte Miniature' WHERE LABEL_ID ='NDP_MINIATURE_TEXT' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'NDP_COLONNES' WHERE LABEL_ID ='NDP_COLONNES' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
             "ADD_SLIDE_VISUEL","ADD_SLIDE_HTML5","ADD_SLIDE_VIDEO","ADD_CTA_LISTE_DEROULANTE",
             "ADD_NDP_LIEN","PDF", "NDP_TAILLE_VISUEL", "AFFICHAGE_FICHE_SERVICE_SHOWROOM", 
             "NDP_TITRE_ANCRE", "ANCHOR", "VISUEL_WEB", "NDP_ADD_SLIDE", 
             "ALERT_PLAN_MOBILE", "ADD_MODELCAR", "NDP_ADD_TOGGLE", "NDP_VISUEL", "NDP_ADD_VERBATIM"
             )
        ');
        }
    }
}
