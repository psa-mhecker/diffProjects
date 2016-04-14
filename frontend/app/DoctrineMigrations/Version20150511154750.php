<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150511154750 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE IF EXISTS psa_modele_config');

        $this->addSql('CREATE TABLE psa_modele_config (FINITION_ORDER INT DEFAULT NULL, MONTEE_GAMME INT DEFAULT NULL, LABEL_LOCAL INT DEFAULT NULL, SHOW_CARAC INT DEFAULT NULL, SHOW_COMPARISONCHART INT DEFAULT NULL, SHOW_COMPARISONCHART_BUTTON_OPEN INT DEFAULT NULL, SHOW_COMPARISONCHART_BUTTON_CLOSE INT DEFAULT NULL, SHOW_COMPARISONCHART_BUTTON_DIFF INT DEFAULT NULL, SHOW_COMPARISONCHART_BUTTON_PRINT INT DEFAULT NULL, CTA_DISCOVER_ORDER INT DEFAULT NULL, CTA_CONFIGURE_DISPLAY INT DEFAULT NULL, CTA_CONFIGURE_ORDER INT DEFAULT NULL, CTA_STOCK_DISPLAY INT DEFAULT NULL, CTA_STOCK_ORDER INT DEFAULT NULL, LANGUETTE_ORDER VARCHAR(255) DEFAULT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, INDEX IDX_87FC717CF1B5AEBC (SITE_ID), INDEX IDX_87FC717C5622E2C2 (LANGUE_ID), PRIMARY KEY(SITE_ID, LANGUE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_modele_config ADD CONSTRAINT FK_87FC717CF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_modele_config ADD CONSTRAINT FK_87FC717C5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');

        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ("NDP_FINITION_ENGINE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FINITION_ENGINE_LABEL_INFORMATION", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ORDRE_AO", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PRIX_CROISSANT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PRIX_DECROISSANT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ORDONNANCEMENT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MONTEE_GAMME", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MONTEE_GAMME_INFOS_UTILISATEUR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ACTIVER_MONTEE_GAMME", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CARACTERISTIQUES_MOTEURS", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_DISPLAY", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_COMPARATIF_VERSION", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_COMPARATIF_VERSION_INFOS_UTILISATEUR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SHOW_COMPARISONCHART", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SHOW_BUTTON_OPEN", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SHOW_BUTTON_CLOSE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SHOW_BUTTON_DIFF", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SHOW_BUTTON_PRINT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_GESTION_CTA_RANGE_BAR_CAR_SELECTOR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CTA_RANGE_BAR_INFOS_UTILISATEUR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CTA_DECOUVRIR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_AFFICHAGE_CTA_OBLIGATOIRE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ORDER", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CTA_CONFIGURER", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_CTA_WEBSERVICE_CONFIGURER", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SHOW_CTA", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CTA_STOCK", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_CTA_WEBSERVICE_WEBSTORE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_CTA_STOCK", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PRIORITE_AFFICHAGE_LANGUETTE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PRIORITE_AFFICHAGE_LANGUETTE_INFOS_UTILISATEUR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PRIORITY_DISPLAY", NULL, 2, NULL, NULL, 1, NULL)
                ');
         $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ("NDP_FINITION_ENGINE", 1, 1, "Finitions / Motorisation"),
                ("NDP_FINITION_ENGINE_LABEL_INFORMATION", 1, 1, "La modification de cet ordonnancement remplacera toutes les surcharges éventuelles par modèle."),
                ("NDP_ORDRE_AO", 1, 1, "Ordre AO"),
                ("NDP_PRIX_CROISSANT", 1, 1, "Prix croissant"),
                ("NDP_PRIX_DECROISSANT", 1, 1, "Prix décroissant"),
                ("NDP_ORDONNANCEMENT", 1, 1, "Ordonnancement"),
                ("NDP_MONTEE_GAMME", 1, 1, "Montée en gamme"),
                ("NDP_MONTEE_GAMME_INFOS_UTILISATEUR", 1, 1, "L\'activation/désactivation de la montée en gamme pour tous les modèles activera/désactivera la montée en gamme sur l\'ensemble des segments de finitions et finitions. Les paramétrages précédents seront remplacés."),
                ("NDP_ACTIVER_MONTEE_GAMME", 1, 1, "Activer la montée en gamme"),
                ("NDP_CARACTERISTIQUES_MOTEURS", 1, 1, "Caractéristiques techniques supplémentaires des moteurs"),
                ("NDP_DISPLAY", 1, 1, "Affichage"),
                ("NDP_COMPARATIF_VERSION", 1, 1, "Tableau comparatif des versions"),
                ("NDP_COMPARATIF_VERSION_INFOS_UTILISATEUR", 1, 1, "Activation du tableau comparatif de versions sur les pages Finitions et Motorisations. Le paramétrage dans les tranches des Showrooms surcharge celui effectué ici."),
                ("NDP_SHOW_COMPARISONCHART", 1, 1, "Tableau comparatif"),
                ("NDP_SHOW_BUTTON_OPEN", 1, 1, "Affichage du bouton Tout déplier"),
                ("NDP_SHOW_BUTTON_CLOSE", 1, 1, "Affichage du bouton Tout refermer"),
                ("NDP_SHOW_BUTTON_DIFF", 1, 1, "Affichage du bouton Uniquement les différences"),
                ("NDP_SHOW_BUTTON_PRINT", 1, 1, "Affichage du bouton Imprimer"),
                ("NDP_GESTION_CTA_RANGE_BAR_CAR_SELECTOR", 1, 1, "Gestion des CTA Range Bar / Car Selector"),
                ("NDP_CTA_RANGE_BAR_INFOS_UTILISATEUR", 1, 1, "Les CTA ci-dessous sont affichés dans la Range Bar ainsi que sur le calque CTA des vignettes véhicules du Car Selector (si activé)."),
                ("NDP_CTA_DECOUVRIR", 1, 1, "CTA Découvrir"),
                ("NDP_AFFICHAGE_CTA_OBLIGATOIRE", 1, 1, "Affichage du CTA obligatoire"),
                ("NDP_ORDER", 1, 1, "Ordre"),
                ("NDP_CTA_CONFIGURER", 1, 1, "CTA Configurer"),
                ("NDP_MSG_CTA_WEBSERVICE_CONFIGURER", 1, 1, "Il est nécessaire d\'activer le Configurateur dans les Sites et webservices PSA afin d\'activer le CTA Configurer."),
                ("NDP_SHOW_CTA", 1, 1, "Affichage du CTA"),
                ("NDP_CTA_STOCK", 1, 1, "CTA Voir les stocks"),
                ("NDP_MSG_CTA_WEBSERVICE_WEBSTORE", 1, 1, "Il est nécessaire d\'activer le webStroe dans les Sites et webservices PSA afin d\'activer le CTA Voir les stocks."),
                ("NDP_MSG_CTA_STOCK", 1, 1, "Le CTA Voir les stocks n\'est pas affiché dans la Range Bar."),
                ("NDP_PRIORITE_AFFICHAGE_LANGUETTE", 1, 1, "Priorité d\'affichage des languettes commerciales"),
                ("NDP_PRIORITE_AFFICHAGE_LANGUETTE_INFOS_UTILISATEUR", 1, 1, "Cette priorisation est utilisée pour l\'affichage des languettes commerciales sur le Car Selector."),
                ("NDP_PRIORITY_DISPLAY", 1, 1, "Priorité d\'affichage")
       ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE  IF EXISTS  psa_modele_config');

         $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_FINITION_ENGINE",
                "NDP_FINITION_ENGINE_LABEL_INFORMATION",
                "NDP_ORDRE_AO",
                "NDP_PRIX_CROISSANT",
                "NDP_PRIX_DECROISSANT",
                "NDP_ORDONNANCEMENT",
                "NDP_MONTEE_GAMME",
                "NDP_MONTEE_GAMME_INFOS_UTILISATEUR",
                "NDP_ACTIVER_MONTEE_GAMME",
                "NDP_CARACTERISTIQUES_MOTEURS",
                "NDP_DISPLAY",
                "NDP_COMPARATIF_VERSION",
                "NDP_COMPARATIF_VERSION_INFOS_UTILISATEUR",
                "NDP_SHOW_COMPARISONCHART",
                "NDP_SHOW_BUTTON_OPEN",
                "NDP_SHOW_BUTTON_CLOSE",
                "NDP_SHOW_BUTTON_DIFF",
                "NDP_SHOW_BUTTON_PRINT",
                "NDP_GESTION_CTA_RANGE_BAR_CAR_SELECTOR",
                "NDP_CTA_RANGE_BAR_INFOS_UTILISATEUR",
                "NDP_CTA_DECOUVRIR",
                "NDP_AFFICHAGE_CTA_OBLIGATOIRE",
                "NDP_ORDER",
                "NDP_CTA_CONFIGURER",
                "NDP_MSG_CTA_WEBSERVICE_CONFIGURER",
                "NDP_SHOW_CTA",
                "NDP_CTA_STOCK",
                "NDP_MSG_CTA_WEBSERVICE_WEBSTORE",
                "NDP_MSG_CTA_STOCK",
                "NDP_PRIORITE_AFFICHAGE_LANGUETTE",
                "NDP_PRIORITE_AFFICHAGE_LANGUETTE_INFOS_UTILISATEUR",
                "NDP_PRIORITY_DISPLAY"
                      )
            ');
        }

    }
}
