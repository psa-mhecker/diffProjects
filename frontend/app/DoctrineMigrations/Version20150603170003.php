<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150603170003 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        //Re introduction de NDP_JE_VEUX car pas présent dans les migrations
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                 "NDP_JE_VEUX",
                 "VIGNETTE_1_3",
                 "VIGNETTE_2_3"
                 )
                '
            );
        }
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ("NDP_LIMIT_LIB", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_NB_TAB_TOOLTIP2", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_JE_VEUX", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VIGNETTE_1_3", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VIGNETTE_2_3", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_3_SOCIAL_NEEDED", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ("NDP_LIMIT_LIB", 1, 1, "caractères max"),
                ("NDP_NB_TAB_TOOLTIP2", 1, 1, "Prend en compte les tranches glissées/déposées qui suivent les tranches rattachées à l’onglet 1."),
                ("NDP_JE_VEUX", 1, 1, "Je veux"),
                ("NDP_VIGNETTE_1_3", 1, 1, "Vignette 1/3"),
                ("NDP_VIGNETTE_2_3", 1, 1, "vignette 2/3"),
                ("NDP_3_SOCIAL_NEEDED", 1, 1, "3 Réseaux sociaux sont nécessaires.")
                ');
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Lien interne/externe' WHERE LABEL_ID ='NDP_LIEN_INT_EXT' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Sous-titre' WHERE LABEL_ID ='NDP_SOUS_TITRE' AND SITE_ID = 1 AND LANGUE_ID = 1");
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
                 "NDP_LIMIT_LIB",
                 "NDP_NB_TAB_TOOLTIP2",
                 "NDP_JE_VEUX",
                 "NDP_VIGNETTE_1_3",
                 "NDP_VIGNETTE_2_3",
                 "NDP_3_SOCIAL_NEEDED"
                 )
                '
            );
        }
    }
}
