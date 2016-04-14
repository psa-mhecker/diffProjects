<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150618105756 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // ajout onglet 'taggage' dans Editorial
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
              (105, 3, 1, 'NDP_CMS_TAGGAGE', 'Cms_NdpTaggage', NULL, NULL, '')
          ");
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
              (106, 2, 13, 'NDP_CMS_TAGGAGE_MENU', 'Cms_NdpNavigationTaggage', NULL, NULL, '')
          ");

        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (124, 106, NULL, 0, NULL, NULL, 'NDP_CMS_TAGGAGE_MENU', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (123, 105, 124, 0, NULL, NULL, 'NDP_CMS_TAGGAGE', NULL, NULL)
           ");

        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (123, 2)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (124, 2)");

        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 123, 2076),
            (2, 124, 2077)
        ');

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_CMS_TAGGAGE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CMS_TAGGAGE_MENU', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_CMS_TAGGAGE', 1, 1, 'Taggage'),
            ('NDP_CMS_TAGGAGE_MENU', 1, 1, 'Taggage')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // onglet 'taggage' dans Editorial
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID IN (123, 124)');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID IN (123, 124)');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID IN (123, 124)');
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID IN (105, 106)');
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
              'DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                    "NDP_CMS_TAGGAGE",
                    "NDP_CMS_TAGGAGE_MENU"
                )'
            );
        }

    }
}
