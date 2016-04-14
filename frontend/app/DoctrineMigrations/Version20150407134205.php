<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150407134205 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PF16_AUTRES_RESEAUX_SOCIAUX', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pf16AutresReseauxSociaux' WHERE `ZONE_ID` = 762");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC68_CONTENU_1_ARTICLE_2_OU_3_VISUELS', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pc68Contenu1Article2Ou3Visuels' WHERE `ZONE_ID` = 766");

        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_COLONNE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_STYLE_NIVEAU1", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_STYLE_NIVEAU2", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_STYLE_NIVEAU3", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_STYLE_NIVEAU4", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_STYLE_NIVEAU5", NULL, 2, NULL, NULL, 1, NULL)
               ');
         $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                 ("NDP_COLONNE", 1, 1, "Colonne"),
                 ("NDP_STYLE_NIVEAU1", 1, 1, "Bouton Bleu foncé"),
                 ("NDP_STYLE_NIVEAU2", 1, 1, "Bouton Bleu clair"),
                 ("NDP_STYLE_NIVEAU3", 1, 1, "Bouton Gris"),
                 ("NDP_STYLE_NIVEAU4", 1, 1, "Lien"),
                 ("NDP_STYLE_NIVEAU5", 1, 1, "Liste déroulante")
        ');
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             ("NDP_COLONNE1",
             "NDP_COLONNE2",
             "NDP_COLONNE3",
             "NDP_DARK_BLUE",
             "NDP_LIGHT_BLUE",
             "NDP_BLANC",
             "NDP_GRIS"
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
