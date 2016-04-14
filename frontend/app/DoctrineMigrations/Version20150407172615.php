<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150407172615 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
               ("NDP_MSG_STICKER_TITRE_PAGE", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_TERMS_CONDITIONS", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_URL_NEWSLETTER", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_VISUEL_16_9_WEB", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_VISUEL_16_9_MOB", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_MASTERPAGE", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_QUICKACCESS", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_LABEL_LANGUETTE", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_LABEL_SITEMAP", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_URL_SITEMAP", NULL, 2, NULL, NULL, 1, NULL),
               ("NDP_WHITE", NULL, 2, NULL, NULL, 1, NULL)
               ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                 ("NDP_MSG_STICKER_TITRE_PAGE", 1, 1, "Sticker le titre de la page"),
                 ("NDP_TERMS_CONDITIONS", 1, 1, "Mentions légales"),
                 ("NDP_URL_NEWSLETTER", 1, 1, "URL de la page d’abonnement"),
                 ("NDP_VISUEL_16_9_WEB", 1, 1, "Visuel 16/6 Web"),
                 ("NDP_VISUEL_16_9_MOB", 1, 1, "Visuel 16/6 Mobile"),
                 ("NDP_MASTERPAGE", 1, 1, "Master Page"),
                 ("NDP_QUICKACCESS", 1, 1, "Quick Access"),
                 ("NDP_LABEL_LANGUETTE", 1, 1, "Libellé de la languette"),
                 ("NDP_LABEL_SITEMAP", 1, 1, "Libellé du plan du site complet"),
                 ("NDP_URL_SITEMAP", 1, 1, "URL du plan du site complet"),
                 ("NDP_WHITE", 1, 1, "Blanc")
        ');
        // this up() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             ("NDP_STICKER_LE_TITRE_DE_LA_PAGE",
             "NDP_TEXTE_COLONNE_1",
             "NDP_TEXTE_COLONNE_2",
             "NDP_PF14",
             "NDP_PF14_RESEAUX_SOCIAUX",
             "NDP_PC12_3_COLONNNES",
             "NDP_PC12_3_COLONNNES2",
             "NDP_PF6_DRAGDROP",
             "NDP_PN3_TOGGLE_ACCORDEON",
             "NDP_PN7_ENTETE",
             "NDP_PT19_ENGAGEMENTS",
             "NDP_PT21",
             "NDP_PT21_NAVIGATION",
             "NDP_PT3",
             "NDP_PT3_JE_VEUX"
             )
            ');
        }

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
