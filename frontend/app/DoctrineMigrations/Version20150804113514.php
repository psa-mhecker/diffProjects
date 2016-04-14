<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150804113514 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_INFO_CLASSIQUE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_INFO_VISUEL_TEXTE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_INFO_SELF", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_INFO_BLANK", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_INFO_POPIN", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_INFO_ZONE_DESCR_CTA", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_INFO_ZONE_DESCR_APPLAT", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_INFO_ZONE_TITRE_APPLAT", NULL, 2, NULL, NULL, 1, NULL)
            ');

        $this->addSql('REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_INFO_CLASSIQUE", 1, 1, "CLASSIQUE = composé du titre et du fil d\'ariane. "),
            ("NDP_INFO_VISUEL_TEXTE", 1, 1, "AVEC VISUEL = composé du titre et du fil d\'ariane ET d\'un visuel et une zone de texte avec 1 ou 2 CTA en applat sur le visuel. "),
            ("NDP_INFO_SELF", 1, 1, "SELF = Ouverture de la page de destination dans la page active. "),
            ("NDP_INFO_BLANK", 1, 1, "BLANK = Ouverture de la page de destination dans un nouvel onglet. "),
            ("NDP_INFO_POPIN", 1, 1, "POPIN = Ouverture de la page de destination dans une popin. "),
            ("NDP_INFO_ZONE_DESCR_CTA", 1, 1, "La zone descriptive et les CTA associés s\'afficheront à droite sur le visuel avec un applat "),
            ("NDP_INFO_ZONE_DESCR_APPLAT", 1, 1, "La couleur de l\'applat de la zone descriptive est à définir en fonction du visuel sélectionné "),
            ("NDP_INFO_ZONE_TITRE_APPLAT", 1, 1, "La couleur de l\'applat de la zone titre est à définir en fonction du visuel sélectionné ")
            ');
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
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                "NDP_INFO_CLASSIQUE",
                "NDP_INFO_VISUEL_TEXTE",
                "NDP_INFO_SELF",
                "NDP_INFO_BLANK",
                "NDP_INFO_POPIN",
                "NDP_INFO_ZONE_DESCR_CTA",
                "NDP_INFO_ZONE_DESCR_APPLAT",
                "NDP_INFO_ZONE_TITRE_APPLAT"
                )');
        }
    }
}
