<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160308153620 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID IN (97, 98, 99, 102, 103, 104, 105, 106, 107, 108, 109, 115, 116)');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID IN (97, 98, 99, 102, 103, 104, 105, 106, 107, 108, 109, 115, 116)');

        $this->addSql('ALTER TABLE `psa_ws_gdg_model_silhouette_site` ADD `COLOR_ID` INT DEFAULT 1');
        $this->replaceTranslations(array(
            'NDP_REF_MODELE_TOUS' => array(
                'expression' => 'Priorité d’affichage des languettes'
            ),
        ));

        $this->upTranslations(array(
            'NDP_MESSAGE_DISPLAY_COLOR' => array(
                'expression' => 'Couleur pour le menu Showroom et les badges « Série spéciale » et « Edition limitée » du Car Selector / Range Bar',
                'bo' => 1
            ),
        ));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 97, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 98, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 99, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 102, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 103, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 104, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 105, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 106, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 107, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 108, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 109, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 115, SITE_ID FROM psa_site');
        $this->addSql('REPLACE INTO psa_directory_site (DIRECTORY_ID, SITE_ID)  SELECT 116, SITE_ID FROM psa_site');

        $this->addSql('ALTER TABLE `psa_ws_gdg_model_silhouette_site` DROP `COLOR_ID`');

        $this->downTranslations(array(
            'NDP_MESSAGE_DISPLAY_COLOR'
        ));
    }
}
