<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150603144756 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // ajout de la migration de données showroom
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
             (102, 1, 5, 'NDP_MIG_SHOWROOM', 'Ndp_Migration_Showroom', '', NULL, '')
          ");

        // Répertoire migration ajouté au répertoire Générale
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (119, NULL, 4, 0, NULL, NULL, 'NDP_MIG_DATA_MIGRATION', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (119, 2)
           ");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 119, 2068),
            (3, 119, 2069)
        ');

        // Sous-répertoire 'Migration Showroom' ajouté au répertoire Migration
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (120, 102, 119, 0, NULL, NULL, 'NDP_MIG_SHOWROOM_MIGRATION', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (120, 2)
        ");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 120, 2070),
            (3, 120, 2071)
        ');

        // Traductions des répertoires
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MIG_DATA_MIGRATION', NULL, 2, NULL, NULL, 1, NULL)");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_MIG_DATA_MIGRATION', 1, 1, 'Migrations de données')");
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MIG_SHOWROOM_MIGRATION', NULL, 2, NULL, NULL, 1, NULL)");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_MIG_SHOWROOM_MIGRATION', 1, 1, 'Migration de données Showroom')");

        // Traductions de l'écran d'accueil de migration des showroom
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MIG_SHOWROOM_MIGRATE_TITLE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_SHOWROOM_TYPE_VN_PUBLISHED', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_SHOWROOM_TYPE_CONCEPT_PUBLISHED', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_SHOWROOM_TYPE_TECHNO_PUBLISHED', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_SHOWROOM_TYPE_NOT_PUBLISHED', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_SHOWROOM_TYPE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_IMPORTER', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_URL_LANGUAGE', NULL, 2, NULL, NULL, 1, NULL)
            ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_MIG_SHOWROOM_MIGRATE_TITLE', 1, 1, 'URLs d\'accueil Délia par langue d\'un showroom'),
            ('NDP_MIG_SHOWROOM_TYPE_VN_PUBLISHED', 1, 1, 'VN publié (ex. : http://www.peugeot.fr/decouvrir/nouvelle-308/5-portes/)'),
            ('NDP_MIG_SHOWROOM_TYPE_CONCEPT_PUBLISHED', 1, 1, 'Concept car publié (ex. : http://www.peugeot.fr/concept-cars-showroom/exalt-car/concept-car/)'),
            ('NDP_MIG_SHOWROOM_TYPE_TECHNO_PUBLISHED', 1, 1, 'Technologie publié (ex. : http://www.peugeot.fr/technologies/)'),
            ('NDP_MIG_SHOWROOM_TYPE_NOT_PUBLISHED', 1, 1, 'Showroom non publié (ex. : http://showroom.peugeot.com/index.php?showroom_id=9175)'),
            ('NDP_MIG_SHOWROOM_TYPE', 1, 1, 'Type d\'url showroom'),
            ('NDP_MIG_IMPORTER', 1, 1, 'Importer'),
            ('NDP_MIG_URL_LANGUAGE', 1, 1, 'URL langue')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // Fonctionnalité Migration de données showroom
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID =102');

        // Répertoire Migration de données showroom
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 120');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 120');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID = 120');

        // Répertoire Migration de données
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 119');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 119');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID = 119');

        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_MIG_SHOWROOM_MIGRATION",
                "NDP_MIG_DATA_MIGRATION",
                "NDP_MIG_SHOWROOM_MIGRATE_TITLE",
                "NDP_MIG_SHOWROOM_TYPE_VN_PUBLISHED",
                "NDP_MIG_SHOWROOM_TYPE_CONCEPT_PUBLISHED",
                "NDP_MIG_SHOWROOM_TYPE_TECHNO_PUBLISHED",
                "NDP_MIG_SHOWROOM_TYPE_NOT_PUBLISHED",
                "NDP_MIG_SHOWROOM_TYPE",
                "NDP_MIG_IMPORTER",
                "NDP_MIG_URL_LANGUAGE"
                )
            ');
        }

    }

}
