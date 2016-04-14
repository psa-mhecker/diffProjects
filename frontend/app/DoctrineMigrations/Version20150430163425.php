<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150430163425 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_VIGNETTE_VIDEO', NULL, 2, NULL, NULL, 1, NULL),          
                ('NDP_COLOR_TITLE_SUBTITLE', NULL, 2, NULL, NULL, 1, NULL), 
                ('NDP_POS_TITLE_SUBTITLE_CTA', NULL, 2, NULL, NULL, 1, NULL),                
                ('NDP_SHOW_COLONNE', NULL, 2, NULL, NULL, 1, NULL),                
                ('NDP_SHOW_BLOC', NULL, 2, NULL, NULL, 1, NULL),
                ('LISTE_DEROULANTE_CTA', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LIEN_INT_EXT', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SLIDESHOW_POPIN', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_VIGNETTE_VIDEO', 1, 1, 'Vignette vidéo'),          
                ('NDP_COLOR_TITLE_SUBTITLE', 1, 1, 'Couleur titre + sous-titre'),
                ('NDP_POS_TITLE_SUBTITLE_CTA', 1, 1, 'Positionnement titre + Sous-titre + CTA'),                
                ('NDP_SHOW_COLONNE', 1, 1, 'Afficher la colonne'),                
                ('NDP_SHOW_BLOC', 1, 1, 'Afficher le bloc'),                
                ('LISTE_DEROULANTE_CTA', 1, 1, 'Liste déroulante de CTA'),            
                ('NDP_LIEN_INT_EXT', 1, 1, 'Lien internet/externe'),         
                ('NDP_SLIDESHOW_POPIN', 1, 1, 'Slideshow (pop-in)')         
                ");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Nombre de tranches correspondant à l\'onglet' WHERE LABEL_ID ='NDP_NB_ZONE' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Type d\'affichage' WHERE LABEL_ID ='NDP_TYPE_AFFICHAGE' AND SITE_ID = 1 AND LANGUE_ID = 1");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
             "NDP_VIGNETTE_VIDEO", "NDP_COLOR_TITLE_SUBTITLE", "NDP_POS_TITLE_SUBTITLE_CTA",
             "NDP_SHOW_COLONNE", "NDP_SHOW_BLOC", "LISTE_DEROULANTE_CTA", "NDP_LIEN_INT_EXT",
             "NDP_SLIDESHOW_POPIN"
             )
        ');
        }
    }
}
