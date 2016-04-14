<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160401113023 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // ajout de lu template de l'outil de dev
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
             (120, 1, 5, 'NDP_DEV_TOOLS', 'Ndp_Develop_Tools', '', NULL, '')
          ");


        // Répertoire de dev en pour le super admin
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (150, NULL, 1, 0, NULL, NULL, 'NDP_DEVELOP', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (150, 1)
           ");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (1, 150, 1028)
        ');

        // Sous-répertoire 'Migration Showroom' ajouté au répertoire Migration
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (155, 120, 150, 0, NULL, NULL, 'NDP_DEVELOP_TOOLS', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (155, 1)
        ");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (1, 155, 1029)
        ');

        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_DEVELOP_TOOLS' => array(
                    'expression' => "Outils développeur",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_DEVELOP' => array(
                    'expression' => "Developpement",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(
            array(
                'NDP_DEVELOP_TOOLS',
                'NDP_DEVELOP',

            )
        );
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID=155 ');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID=155 ');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID=155 ');

        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID=150 ');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID=150 ');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID=150 ');

        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID=120 ');

    }
}
