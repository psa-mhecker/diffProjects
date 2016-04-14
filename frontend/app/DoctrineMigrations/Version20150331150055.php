<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150331150055 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // generique
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("CTAFORM", NULL, 2, NULL, NULL, 1, NULL),
                ("FORMAT_ATTENDU", NULL, 2, NULL, NULL, 1, NULL),
                ("FORMAT_MIN", NULL, 2, NULL, NULL, 1, NULL),
                ("GALLERYFORM", NULL, 2, NULL, NULL, 1, NULL),
                ("PUSH", NULL, 2, NULL, NULL, 1, NULL)
                ');

        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("CTAFORM", 1, 1, "CTA"),
                ("FORMAT_ATTENDU", 1, 1, "Format Attendu"),
                ("FORMAT_MIN", 1, 1, "Format min"),
                ("GALLERYFORM", 1, 1, "Galerie"),
                ("PUSH", 1, 1, "Push")
        ');
        // pf6
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES

                ("NDP_DARK_BLUE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_GREY", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_HORIZONTALE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LIGHT_BLUE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LIGNE_SEPARATRICE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SOUS_TITRE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_STYLE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TEXTE_COLONNE_1", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TEXTE_COLONNE_2", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TITRE_ZONE_TEXTE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VERTICALE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VISUEL_1_GAUCHE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VISUEL_2_DROITE", NULL, 2, NULL, NULL, 1, NULL)
                ');
         $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_DARK_BLUE", 1, 1, "Bleu foncé"),
                ("NDP_GREY", 1, 1, "Gris"),
                ("NDP_HORIZONTALE", 1, 1, "Horizontale"),
                ("NDP_LIGHT_BLUE", 1, 1, "bleu clair"),
                ("NDP_LIGNE_SEPARATRICE", 1, 1, "Ligne séparatrice"),
                ("NDP_PF6_DRAGDROP", 1, 1, "Drag and Drop"),
                ("NDP_SOUS_TITRE", 1, 1, "Sous titre"),
                ("NDP_STYLE", 1, 1, "Style"),
                ("NDP_TEXTE_COLONNE_1", 1, 1, "Texte colonne 1"),
                ("NDP_TEXTE_COLONNE_2", 1, 1, "Texte colonne 2"),
                ("NDP_TITRE_ZONE_TEXTE", 1, 1, "Titre zone texte"),
                ("NDP_VERTICALE", 1, 1, "Verticale"),
                ("NDP_VISUEL_2_DROITE", 1, 1, "Visuel droite"),
                ("NDP_VISUEL_1_GAUCHE", 1, 1, "Visuel gauche")
                ');

        //pc12
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_AFTER", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CAROUSEL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_COLONNE1", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_COLONNE2", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_COLONNE3", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DISPLAY_SERVICE_SHOWROOM", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MOBILE_DISPLAY_MODE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PC12_3_COLONNNES", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PC12_3_COLONNNES2", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TEXTE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TITRE_COLONNE", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_AFTER", 1, 1, "Après"),
                ("NDP_CAROUSEL", 1, 1, "Carousel"),
                ("NDP_COLONNE1", 1, 1, "Colonne 1"),
                ("NDP_COLONNE2", 1, 1, "Colonne 2"),
                ("NDP_COLONNE3", 1, 1, "Colonne 3"),
                ("NDP_DISPLAY_SERVICE_SHOWROOM", 1, 1, "Afficher le service Showroom"),
                ("NDP_MOBILE_DISPLAY_MODE", 1, 1, "Affichage Mobile"),
                ("NDP_TEXTE", 1, 1, "Text"),
                ("NDP_TITRE_COLONNE", 1, 1, "Titre colonne")

        ');
        
        // pc16
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_ACTIVE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_AVIS_CLIENT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BLANK", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DESACTIVE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_IDENTITE_DU_CLIENT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LABEL_ORIGINE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LIBELLE_SITE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MODE_OUVERTURE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_POPIN", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SELF", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SITE_PUBLICATION", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_URL_SITE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_VERBATIM", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_ACTIVE", 1, 1, "Activé"),
                ("NDP_AVIS_CLIENT", 1, 1, "Avis client"),
                ("NDP_BLANK", 1, 1, "_blank"),
                ("NDP_DESACTIVE", 1, 1, "Désactivé"),
                ("NDP_IDENTITE_DU_CLIENT", 1, 1, "Identité du client"),
                ("NDP_LABEL_ORIGINE", 1, 1, "Label origine"),
                ("NDP_LIBELLE_SITE", 1, 1, "Libellé du site"),
                ("NDP_MODE_OUVERTURE", 1, 1, "Mode d\'ouverture"),
                ("NDP_POPIN", 1, 1, "popin"),
                ("NDP_SELF", 1, 1, "_self"),
                ("NDP_SITE_PUBLICATION", 1, 1, "Site de publication"),
                ("NDP_URL_SITE", 1, 1, "Url du site"),
                ("NDP_VERBATIM", 1, 1, "Verbatim")
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
             "CTAFORM",
             "FORMAT_ATTENDU",
             "FORMAT_MIN",
             "GALLERYFORM",
             "PUSH",
             "NDP_DARK_BLUE",
             "NDP_GREY",
             "NDP_HORIZONTALE",
             "NDP_LIGHT_BLUE",
             "NDP_LIGNE_SEPARATRICE",
             "NDP_PF6_DRAGDROP",
             "NDP_SOUS_TITRE",
             "NDP_STYLE",
             "NDP_TEXTE_COLONNE_1",
             "NDP_TEXTE_COLONNE_2",
             "NDP_TITRE_ZONE_TEXTE",
             "NDP_VERTICALE",
             "NDP_VISUEL_1_GAUCHE",
             "NDP_VISUEL_2_DROITE",
             "NDP_AFTER",
             "NDP_CAROUSEL",
             "NDP_COLONNE1",
             "NDP_COLONNE2",
             "NDP_COLONNE3",
             "NDP_DISPLAY_SERVICE_SHOWROOM",
             "NDP_MOBILE_DISPLAY_MODE",
             "NDP_PC12_3_COLONNNES",
             "NDP_PC12_3_COLONNNES2",
             "NDP_TEXTE",
             "NDP_TITRE_COLONNE",
             "NDP_ACTIVE",
             "NDP_AVIS_CLIENT",
             "NDP_BLANK",
             "NDP_DESACTIVE",
             "NDP_IDENTITE_DU_CLIENT",
             "NDP_LABEL_ORIGINE",
             "NDP_LIBELLE_SITE",
             "NDP_MODE_OUVERTURE",
             "NDP_POPIN",
             "NDP_SELF",
             "NDP_SITE_PUBLICATION",
             "NDP_URL_SITE",
             "NDP_VERBATIM"
             )
        ');
        }

    }
}
