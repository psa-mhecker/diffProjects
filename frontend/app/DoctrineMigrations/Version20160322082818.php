<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160322082818 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql('ALTER TABLE psa_rewrite MODIFY REWRITE_ID INT(11)');
        // ajout de la migration de données showroom
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
             (110, 1, 5, 'NDP_REDIRECT_301', 'Ndp_Administration_Redirect', '', NULL, '')
          ");
        // Répertoire migration ajouté au répertoire Générale
        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (145, 110, 4, 0, NULL, NULL, 'NDP_DIR_REDIRECT_301', NULL, NULL)
           ");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) SELECT 145, s.SITE_ID FROM psa_site s WHERE s.SITE_ID >=2");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) SELECT p.PROFILE_ID,145,(SELECT MAX(pd.PROFILE_DIRECTORY_ORDER)+1 FROM psa_profile_directory pd ) FROM psa_profile p WHERE p.SITE_ID > 1 AND p.PROFILE_LABEL !="TRADUCTEUR" ');

        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_REDIRECT_301' => array(
                    'expression' => "Redirection 301",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_DIR_REDIRECT_301' => array(
                    'expression' => "gestion Url 301",
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
        $this->addSql('ALTER TABLE psa_rewrite MODIFY REWRITE_ID INT(11) NOT NULL');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID =145');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID =145');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID =145');
        $this->addSql('DELETE FROM psa_template WHERE DIRECTORY_ID =110');
        $this->downTranslations(
            array(
                'NDP_REDIRECT_301',
                'NDP_DIR_REDIRECT_301',
            )
        );

    }
}
