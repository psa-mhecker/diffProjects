<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150511133216 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // FIX template cppv2 reused - fonctionnalite configuration WS
        $this->addSql("UPDATE psa_template SET TEMPLATE_TYPE_ID = 1, TEMPLATE_GROUP_ID = 5, TEMPLATE_LABEL = 'NDP_REF_CONFIGURATION_WS', TEMPLATE_PATH =  'Ndp_ConfigurationWS' WHERE  TEMPLATE_ID =322");

        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
 (118, 322, 4, 0, NULL, NULL, 'NDP_REF_CONFIGURATION_WS', NULL, NULL)");

        $this->addSql("DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 118");
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 118');
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (118, 2)");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (2, 118, 2068)');

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_REF_CONFIGURATION_WS', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_V3D', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_ANNUPDV', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_MOTEUR_CONFIG_PROD', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_MOTEUR_CONFIG_PREVIEW', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_GEST_GAMME', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_ACCESSOIRES_AOA', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_SFG', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_WEBSTORE', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_REF_CONFIGURATION_WS', 1, 1, 'Configuration des webservices (url)'),
            ('WS_V3D', 1, 1, 'Baie visuels 3D'),
            ('WS_ANNUPDV', 1, 1, 'Webservice Annuaire PDV'),
            ('WS_MOTEUR_CONFIG_PROD', 1, 1, 'Webservice Moteur de configuration (Prod)'),
            ('WS_MOTEUR_CONFIG_PREVIEW', 1, 1, 'Webservice Moteur de configuration (Preview)'),
            ('WS_GEST_GAMME', 1, 1, 'Webservice Gamme'),
            ('WS_ACCESSOIRES_AOA', 1, 1, 'Webservice Accessoires AOA'),
            ('WS_SFG', 1, 1, 'Webservice Financement SFG'),
            ('WS_WEBSTORE', 1, 1, 'Webservice webStore')
           ");

        $this->addSql('DELETE FROM psa_site_webservice');
        $this->addSql('DELETE FROM psa_liste_webservices');
        $this->addSql("INSERT INTO psa_liste_webservices (ws_id, ws_name, ws_url) VALUES
            (1, 'WS_V3D', 'http://visuel3d.peugeot.com'),
            (2, 'WS_ANNUPDV', 'https://annuaire-pdv.servicesgp.mpsa.com'),
            (3, 'WS_MOTEUR_CONFIG_PROD', 'http://wssoap.inetpsa.com/cfg'),
            (4, 'WS_MOTEUR_CONFIG_PREVIEW', 'lot 2'),
            (5, 'WS_GEST_GAMME', ''),
            (6, 'WS_ACCESSOIRES_AOA', 'http://wssoap.inetpsa.com/aoa'),
            (7, 'WS_SFG', 'https://sfg-bpf.servicesgp.mpsa.com/##PAYS##'),
            (8, 'WS_WEBSTORE', 'http://ws-store.peugeot.inet.psa.com')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // fonctionnalite configuration WS
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 118');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 118');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID = 118');
        $this->addSql('DELETE FROM psa_site_webservice');
        $this->addSql('DELETE FROM psa_liste_webservices');

        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_REF_CONFIGURATION_WS",
                "WS_V3D",
                "WS_ANNUPDV",
                "WS_MOTEUR_CONFIG_PROD",
                "WS_MOTEUR_CONFIG_PREVIEW",
                "WS_GEST_GAMME",
                "WS_ACCESSOIRES_AOA",
                "WS_SFG",
                "WS_WEBSTORE"
                )
            ');
        }

    }
}
