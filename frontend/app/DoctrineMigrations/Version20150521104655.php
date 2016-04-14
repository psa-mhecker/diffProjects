<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150521104655 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
     
        $this->addSql('DROP TABLE IF EXISTS psa_modele_config');

        $this->addSql('CREATE TABLE psa_model_config (FINISHING_ORDER INT DEFAULT NULL, UPSELLING TINYINT(1) DEFAULT NULL, LOCAL_LABEL INT DEFAULT NULL, SHOW_CARAC TINYINT(1) DEFAULT NULL, SHOW_COMPARISONCHART TINYINT(1) DEFAULT NULL, SHOW_COMPARISONCHART_BUTTON_OPEN TINYINT(1) DEFAULT NULL, SHOW_COMPARISONCHART_BUTTON_CLOSE TINYINT(1) DEFAULT NULL, SHOW_COMPARISONCHART_BUTTON_DIFF TINYINT(1) DEFAULT NULL, SHOW_COMPARISONCHART_BUTTON_PRINT TINYINT(1) DEFAULT NULL, CTA_DISCOVER_ORDER INT DEFAULT NULL, CTA_CONFIGURE_DISPLAY TINYINT(1) DEFAULT NULL, CTA_CONFIGURE_ORDER INT DEFAULT NULL, CTA_STOCK_DISPLAY TINYINT(1) DEFAULT NULL, CTA_STOCK_ORDER INT DEFAULT NULL, STRIP_ORDER VARCHAR(255) DEFAULT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, INDEX IDX_976C090EF1B5AEBC (SITE_ID), INDEX IDX_976C090E5622E2C2 (LANGUE_ID), PRIMARY KEY(SITE_ID, LANGUE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_model_config ADD CONSTRAINT FK_976C090EF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_model_config ADD CONSTRAINT FK_976C090E5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        
       
        $changeKeyTradToEnglish = array(
            "NDP_FINITION_ENGINE" => "NDP_FINISHING_ENGINE",
            "NDP_FINITION_ENGINE_LABEL_INFORMATION" => "NDP_MSG_FINISHING_ENGINE",
            "NDP_ORDRE_AO" => "NDP_ORDER_AO",
            "NDP_PRIX_CROISSANT" => "NDP_PRICE_ASC",
            "NDP_PRIX_DECROISSANT" => "NDP_PRICE_DESC",
            "NDP_ORDONNANCEMENT" => "NDP_ORDERING",
            "NDP_MONTEE_GAMME" => "NDP_UPSELLING",
            "NDP_MONTEE_GAMME_INFOS_UTILISATEUR" => "NDP_MSG_UPSELLING",
            "NDP_ACTIVER_MONTEE_GAMME" => "NDP_ENABLE_UPSELLING",
            "NDP_CARACTERISTIQUES_MOTEURS" => "NDP_ENGINE_SPECIFICATIONS",
            "NDP_COMPARATIF_VERSION" => "NDP_COMPARISON_VERSION",
            "NDP_COMPARATIF_VERSION_INFOS_UTILISATEUR" => "NDP_MSG_COMPARISON_VERSION",
            "NDP_CTA_RANGE_BAR_INFOS_UTILISATEUR" => "NDP_MSG_CTA_RANGE_BAR",
            "NDP_CTA_DECOUVRIR" => "NDP_CTA_DISCOVER",
            "NDP_AFFICHAGE_CTA_OBLIGATOIRE" => "NDP_DISPLAY_CTA_REQUIRED",
            "NDP_CTA_CONFIGURER" => "NDP_CTA_CONFIG",
            "NDP_MSG_CTA_WEBSERVICE_CONFIGURER" => "NDP_MSG_CTA_WEBSERVICE_CONFIG",
            "NDP_PRIORITE_AFFICHAGE_LANGUETTE" => "NDP_STRIP_PRIORITY_DISPLAY",
            "NDP_PRIORITE_AFFICHAGE_LANGUETTE_INFOS_UTILISATEUR" => "NDP_MSG_STRIP_PRIORITY_DISPLAY",
        );

        foreach ($changeKeyTradToEnglish as $oldKey => $newKey) {
            $this->addSql('UPDATE psa_label SET LABEL_ID = "'.$newKey.'" WHERE LABEL_ID="'.$oldKey.'"');
            $this->addSql('UPDATE psa_label_langue_site SET LABEL_ID = "'.$newKey.'" WHERE LABEL_ID="'.$oldKey.'"');
        }
        $this->addSql('UPDATE psa_label_langue_site SET  LABEL_TRANSLATE="Référentiel Gestion véhicules – Local Tous modèles" WHERE LABEL_ID=  "NDP_REF_MODELE_TOUS"');
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ("NDP_NEW", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SPECIAL_OFFER", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SPECIAL_SERIE", NULL, 2, NULL, NULL, 1, NULL)'
        );

        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ("NDP_NEW", 1, 1, "Nouveauté"),
                ("NDP_SPECIAL_OFFER", 1, 1, "Offre spéciale"),
                ("NDP_SPECIAL_SERIE", 1, 1, "Série spéciale")'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE psa_model_config');

        $changeKeyTradToEnglish = array(
            "NDP_FINISHING_ENGINE" => "NDP_FINITION_ENGINE",
            "NDP_MSG_FINISHING_ENGINE_LABEL" => "NDP_MSG_FINITION_ENGINE_LABEL_INFORMATION",
            "NDP_ORDER_AO" => "NDP_ORDRE_AO",
            "NDP_PRICE_ASC" => "NDP_PRIX_CROISSANT",
            "NDP_PRICE_DESC" => "NDP_PRIX_DECROISSANT",
            "NDP_ORDERING" => "NDP_ORDONNANCEMENT",
            "NDP_UPSELLING" => "NDP_MONTEE_GAMME",
            "NDP_MSG_UPSELLING" => "NDP_MONTEE_GAMME_INFOS_UTILISATEUR",
            "NDP_ENABLE_UPSELLING" => "NDP_ACTIVER_MONTEE_GAMME",
            "NDP_ENGINE_SPECIFICATIONS" => "NDP_CARACTERISTIQUES_MOTEURS",
            "NDP_COMPARISON_VERSION" => "NDP_COMPARATIF_VERSION",
            "NDP_MSG_COMPARISON_VERSION" => "NDP_COMPARATIF_VERSION_INFOS_UTILISATEUR",
            "NDP_MSG_CTA_RANGE_BAR" => "NDP_CTA_RANGE_BAR_INFOS_UTILISATEUR",
            "NDP_CTA_DISCOVER" => "NDP_CTA_DECOUVRIR",
            "NDP_DISPLAY_CTA_REQUIRED" => "NDP_AFFICHAGE_CTA_OBLIGATOIRE",
            "NDP_CTA_CONFIG" => "NDP_CTA_CONFIGURER",
            "NDP_MSG_CTA_WEBSERVICE_CONFIG" => "NDP_MSG_CTA_WEBSERVICE_CONFIGURER",
            "NDP_STRIP_PRIORITY_DISPLAY" => "NDP_PRIORITE_AFFICHAGE_LANGUETTE",
            "NDP_MSG_STRIP_PRIORITY_DISPLAY" => "NDP_PRIORITE_AFFICHAGE_LANGUETTE_INFOS_UTILISATEUR"
        );


        foreach ($changeKeyTradToEnglish as $newKey => $oldKey) {
            $this->addSql('UPDATE psa_label SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
            $this->addSql('UPDATE psa_label_langue_site SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
        }

        $this->addSql('DELETE FROM psa_label WHERE LABEL_ID IN ("NDP_REF_MODELE_TOUS","NDP_NEW","NDP_SPECIAL_OFFER","NDP_SPECIAL_SERIE")');
        $this->addSql('DELETE FROM psa_label_langue_site WHERE LABEL_ID IN ("NDP_REF_MODELE_TOUS","NDP_NEW","NDP_SPECIAL_OFFER","NDP_SPECIAL_SERIE")');
    }
}
