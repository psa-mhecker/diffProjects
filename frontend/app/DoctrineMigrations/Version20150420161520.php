<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150420161520 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
         $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                 ("NDP_CINEMASCOPE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TIMING_SLIDE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TOGGLE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_TOGGLE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DISPLAY_FICHE_SERVICE", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_CINEMASCOPE", 1, 1, "Visuel cinemascope"),
                ("NDP_TIMING_SLIDE", 1, 1, "Temps défilement du diaporama en seconde"),
                ("NDP_TOGGLE", 1, 1, "Libellé toggle"),
                ("NDP_MSG_TOGGLE", 1, 1, "Mode d\'ouverture des toggles à l\'arrivée sur la page :"),
                ("NDP_DISPLAY_FICHE_SERVICE", 1, 1, "Affichage fiche service showroom")
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
