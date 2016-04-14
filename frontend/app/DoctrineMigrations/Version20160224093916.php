<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160224093916 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID=106 ');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID=123');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID=124');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID=124');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID=123');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID=123');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID=124');



    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES (106, 2, 13, "NDP_CMS_TAGGAGE_MENU", "Cms_NdpNavigationTaggage", null, null, "")');
        $this->addSql("INSERT INTO psa_directory
              (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT)
              VALUES
              (124, 106, null, 0, null, null, 'NDP_CMS_TAGGAGE_MENU', null, null),
              (123, 105, 124, 0, null, null, 'NDP_CMS_TAGGAGE', null, null)

            ");

        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (124, 2)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (124, 14)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (124, 15)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (124, 16)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (124, 17)");

        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (123, 2)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (123, 14)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (123, 15)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (123, 16)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (123, 17)");

        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (2, 124, 2079)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (68, 124, 68080)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (73, 124, 73080)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (78, 124, 78080)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (83, 124, 83080)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (2, 123, 2080)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (68, 123, 68081)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (73, 123, 73081)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (78, 123, 78081)");
        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (83, 123, 83081)");
    }
}
