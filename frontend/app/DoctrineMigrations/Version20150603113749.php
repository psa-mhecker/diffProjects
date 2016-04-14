<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150603113749 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // ajout des fonctionnalites referentiel faq (rubrique et category)
         $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
              (104, 1, 5, 'NDP_REF_FAQ_CATEGORY', 'Ndp_FaqCategory', NULL, NULL, ''),
              (103, 1, 5, 'NDP_REF_FAQ_RUBRIQUE', 'Ndp_FaqRubrique', NULL, NULL, '')
          ");

        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (121, 104, 62, 0, NULL, NULL, 'NDP_REF_FAQ_CATEGORY', NULL, NULL),
            (122, 103, 62, 0, NULL, NULL, 'NDP_REF_FAQ_RUBRIQUE', NULL, NULL)
           ");

        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (121, 2),
            (122, 2)
           ");

        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 121, 2072),
            (2, 122, 2073)
        ');

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_REF_FAQ_CATEGORY', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_FAQ_RUBRIQUE', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_REF_FAQ_CATEGORY', 1, 1, 'Faq - Catégorie'),
            ('NDP_REF_FAQ_RUBRIQUE', 1, 1, 'Faq - Rubrique')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // fonctionnalite referentiel faq (cat et rub)
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID IN (121, 122)');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID IN (121, 122)');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID IN (121, 122)');
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID IN (104, 103)');

        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_REF_FAQ_CATEGORY",
                "NDP_REF_FAQ_RUBRIQUE"
                )
            ');
        }

    }
}
