<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150820104138 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE = "L\'affichage de cette présentation peut être paramétré jusqu\'à la date du Reveal. Dans ce cas, la date de fin de publication de la page doit correspondre à la date du Reveal.<br>Une Welcome Page Reveal / Annonce du lancement commercial doit également être programmée pour s\'afficher à la fin de publication de la Welcome Page Annonce du Reveal." WHERE LABEL_ID ="NDP_MSG_ANNOUNCEMENT_REVEAL" AND SITE_ID = 1 AND LANGUE_ID = 1');
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE = "L’affichage de cette présentation peut être paramétré jusqu’à la date du lancement commercial. Dans ce cas, la date de fin de publication de la page doit correspondre à la date du lancement commercial.<br>Une Welcome Page Commercialisation doit également être programmée pour s’afficher à la fin de publication de la Welcome Page Reveal / Annonce du lancement commercial." WHERE LABEL_ID ="NDP_MSG_ANNOUNCEMENT_LAUNCH" AND SITE_ID = 1 AND LANGUE_ID = 1');
        
        
        
        $this->addsql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_VISUAL_MORE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_TO_LEFT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_TO_RIGHT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_1_TO_6_VISUALS', NULL, 2, NULL, NULL, 1, NULL)
            

            ");
        $this->addsql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_VISUAL_MORE', 1, 1, 'Visuel(s)'),
            ('NDP_TO_LEFT', 1, 1, 'A gauche'),
            ('NDP_TO_RIGHT', 1, 1, 'A droite'),
            ('NDP_1_TO_6_VISUALS', 1, 1, 'De 1 à 6 visuels.\nSeul le premier est obligatoire.')

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
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_VISUAL_MORE",
                    "NDP_TO_LEFT",
                    "NDP_TO_RIGHT",
                    "NDP_1_TO_6_VISUALS"
                )'
            );
        }
    }
}
