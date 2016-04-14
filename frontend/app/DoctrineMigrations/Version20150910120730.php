<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150910120730 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_MSG_COMPORTEMENT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_LIBELLE_1ER_LIEN", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_URL_1ER_LIEN", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_MODE_OUVERTURE_CAR_SELECTOR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_MODEL_REFERENTIEL_VEHICULE", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_MSG_COMPORTEMENT", 1, 1, "FULL = au clic sur le nom du modèle le user est dirigé vers le showroom. Tous les regroupements modèle /silhouette de la gamme doivent être présents dans la range bar. LIGHT = pas d\'expand banner avec les differents regroupement modèle / silhouette"),
                ("NDP_MSG_LIBELLE_1ER_LIEN", 1, 1, "Renseignez le libellé pour rediriger le user vers le carselector"),
                ("NDP_MSG_URL_1ER_LIEN", 1, 1, "Renseignez l\'url pour rediriger le user vers le carselector"),
                ("NDP_MSG_MODE_OUVERTURE_CAR_SELECTOR", 1, 1, "SELF = ouverture de la page de destination dans la page active. BLANK = ouverture de la page de destination dans un nouvel onglet"),
                ("NDP_MSG_MODEL_REFERENTIEL_VEHICULE", 1, 1, "Les modèles sont issus du référentiel VEHICULE tout comme les libellés, visuels, prix...")
        ');

        $this->addSql("UPDATE psa_label_langue_site set LABEL_TRANSLATE='Max = 13 modèles.' WHERE LABEL_ID = 'NDP_MSG_MAX_RANGERBAR'");
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
             "NDP_MSG_COMPORTEMENT",
             "NDP_MSG_LIBELLE_1ER_LIEN",
             "NDP_MSG_URL_1ER_LIEN",
             "NDP_MSG_MODE_OUVERTURE_CAR_SELECTOR",
             "NDP_MSG_MODEL_REFERENTIEL_VEHICULE"
             )
        ');
        }

        $this->addSql("UPDATE psa_label_langue_site set LABEL_TRANSLATE='L\'utilisateur doit sélectionner au moins 1 modèle et jusqu\'à 13 modèles maximum.' WHERE LABEL_ID = 'NDP_MSG_MAX_RANGERBAR'");
    }
}
