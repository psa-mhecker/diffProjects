<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150421171746 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NPD_AFFICHAGE_VISUEL', NULL, 2, NULL, NULL, 1, NULL),
                ('NPD_A_GAUCHE_DU_TEXTE', NULL, 2, NULL, NULL, 1, NULL),
                ('NPD_A_DROITE_DU_TEXTE', NULL, 2, NULL, NULL, 1, NULL),
                ('CTA_VISUEL', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CTA', NULL, 2, NULL, NULL, 1, NULL)
                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NPD_AFFICHAGE_VISUEL', 1, 1, 'Affichage de visuel'),
            ('NPD_A_GAUCHE_DU_TEXTE', 1, 1, 'A gauche du texte'),
            ('NPD_A_DROITE_DU_TEXTE', 1, 1, 'A droite du texte'),
            ('CTA_VISUEL', 1, 1, 'Visuel CTA'),
            ('NDP_CTA', 1, 1, 'CTA')
            ");

        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Ajouter' WHERE LABEL_ID ='FORM_BUTTON_ADD_MULTI' AND SITE_ID = 1 AND LANGUE_ID = 1");
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
             "NPD_AFFICHAGE_VISUEL","NPD_A_GAUCHE_DU_TEXTE","NPD_A_DROITE_DU_TEXTE","CTA_VISUEL",
             "NDP_CTA"
             )
        ');
        }

    }
}
