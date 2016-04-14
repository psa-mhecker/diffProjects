<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150826111058 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE psa_accessoires_site (MAX_ACCESSOIRES INT NOT NULL, MAX_ACCESSOIRES_UNIVERS INT NOT NULL, PRODUITS_DERIVES TINYINT(1) NOT NULL, CTA_ERREUR INT NOT NULL, CTA_ERREUR_ID INT DEFAULT NULL, CTA_ERREUR_ACTION VARCHAR(255) DEFAULT NULL, CTA_ERREUR_TITLE VARCHAR(255) DEFAULT NULL, CTA_ERREUR_STYLE VARCHAR(255) DEFAULT NULL, CTA_ERREUR_TARGET VARCHAR(255) DEFAULT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, INDEX IDX_14B8AD22F1B5AEBC (SITE_ID), INDEX IDX_14B8AD225622E2C2 (LANGUE_ID), PRIMARY KEY(SITE_ID, LANGUE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_accessoires (SITE_ID INT NOT NULL, MEDIA_ID INT NOT NULL, INDEX IDX_7ED82D014E107D9 (MEDIA_ID), PRIMARY KEY(SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_accessoires_site ADD CONSTRAINT FK_14B8AD22F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_accessoires_site ADD CONSTRAINT FK_14B8AD225622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_accessoires ADD CONSTRAINT FK_7ED82D0F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_accessoires ADD CONSTRAINT FK_7ED82D014E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID)');

        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
             (350, 1, 5, 'NDP_VISU_ACCESS', 'Ndp_AccessoiresCentral', '', NULL, '')
          ");
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
             (351, 1, 5, 'NDP_PARAMS_ACCESS', 'Ndp_AccessoiresSite', '', NULL, '')
          ");
        // Répertoire Accessoires ajouté au répertoire Générale
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (250, NULL, 4, 0, NULL, NULL, 'NDP_ACCESORIES', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (250, 2)
           ");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 250, 2070)
        ');

        // Sous-répertoire 'Accessoires visuel' ajouté au répertoire Accessoires
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (251, 350, 250, 0, NULL, NULL, 'NDP_ACCESORIES_VISU', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (251, 2)
        ");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 251, 2070)
        ');
        // Sous-répertoire 'Accessoires visuel' ajouté au répertoire Accessoire
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (252, 351, 250, 0, NULL, NULL, 'NDP_ACCESORIES_PARAMS', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (252, 2)
        ");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 252, 2071)
        ');

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_ACCESORIES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ACCESORIES_PARAMS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ACCESORIES_VISU', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_PARAM_DEFAULT_VISUAL_ACC', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PRESENTATION_ACCESSORIES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PRESENTATION_ACCESSORIES_AOA_NEEDED', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MAX_ACCESSOIRES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MAX_ACCESSOIRES_UNIVERS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ACCESORIES_VISU_DEFAULT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_LINK_DERIVAIES_NEEDED', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_LINK_STORE_DERIVATES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CTA_ERROR_ACCESSORIE', NULL, 2, NULL, NULL, 1, NULL)
            ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_ACCESORIES', 1, 1, 'Accessoires'),
            ('NDP_ACCESORIES_PARAMS', 1, 1, 'Paramétrage accessoires'),
            ('NDP_ACCESORIES_VISU', 1, 1, 'Visuel accessoires'),
            ('NDP_MSG_PARAM_DEFAULT_VISUAL_ACC', 1, 1, 'Paramétrage du visuel accessoire par défaut'),
            ('NDP_PRESENTATION_ACCESSORIES', 1, 1, 'Présentation des accessoires'),
            ('NDP_PRESENTATION_ACCESSORIES_AOA_NEEDED', 1, 1, 'Le webservice Accessoires AOA est nécessaire à l’affichage des fiches accessoires.'),
            ('NDP_MAX_ACCESSOIRES', 1, 1, 'Nombre maximum d’accessoires'),
            ('NDP_MAX_ACCESSOIRES_UNIVERS', 1, 1, 'Nombre maximum d’accessoires par univers'),
            ('NDP_ACCESORIES_VISU_DEFAULT', 1, 1, 'Visuel accessoires par défaut'),
            ('NDP_LINK_DERIVAIES_NEEDED', 1, 1, 'Il est nécessaire d’activer la Boutique Produits dérivés afin d’activer le lien.'),
            ('NDP_LINK_STORE_DERIVATES', 1, 1, 'Lien boutique produits dérivés'),
            ('NDP_CTA_ERROR_ACCESSORIE', 1, 1, 'CTA du message d’erreur (accessoires)')
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE IF EXISTS psa_accessoires_site');
        $this->addSql('DROP TABLE IF EXISTS psa_accessoires');
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID = 350');
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID = 351');

        // Répertoire Migration de données showroom
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 252');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 252');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID = 252');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 251');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 251');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID = 251');

        // Répertoire Migration de données
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 250');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 250');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID = 250');
        
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_ACCESORIES",
                "NDP_MSG_PARAM_DEFAULT_VISUAL_ACC",
                "NDP_ACCESORIES_PARAMS",
                "NDP_ACCESORIES_VISU",
                "NDP_PRESENTATION_ACCESSORIES_AOA_NEEDED",
                "NDP_PRESENTATION_ACCESSORIES",
                "NDP_MAX_ACCESSOIRES",
                "NDP_MAX_ACCESSOIRES_UNIVERS",
                "NDP_ACCESORIES_VISU_DEFAULT",
                "NDP_CTA_ERROR_ACCESSORIE",
                "NDP_LINK_DERIVAIES_NEEDED",
                "NDP_LINK_STORE_DERIVATES"
                )
            ');
        }
    }
}
