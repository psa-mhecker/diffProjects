<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150716143902 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_REF_SITE_WS_PSA', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_SITE_DOMAIN_NAME', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CONF_VP', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_POPIN_TRANSITION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CONFIRM_POPIN_TRANSITION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_STORE_SHOWROOM', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_STORE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_WEB_FICHE_ACCESSOIRES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_MOB_FICHE_ACCESSOIRES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_WEB_ACCUEIL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_WEB_CONNEXION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_MOB_ACCUEIL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_MOB_CONNEXION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PEUGEOT_WEBSTORE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PARCOURS_WEBSTORE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PARCOURS_PDV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PARCOURS_REGIONAL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PARCOURS_PRODUIT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_WEB_MOB_WEBSTORE_PRODUITS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_WEB_MOB', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PEUGEOT_SERVICE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PEUGEOT_ENVIRONNEMENT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PEUGEOT_PRO', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PEUGEOT_WEBSTORE_PRO', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_STORE_PRODUIT_DERIVES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PEUGEOT_SCOOTER', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PEUGEOT_CYCLES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MU_BY_PEUGEOT', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_REF_SITE_WS_PSA', 1, 1, 'Sites et WebServices PSA'),
            ('NDP_SITE_DOMAIN_NAME', 1, 1, 'Nom de domaine du site pays'),
            ('NDP_CONF_VP', 1, 1, 'Configurateur VP'),
            ('NDP_POPIN_TRANSITION', 1, 1, 'Pop-in de transistion'),
            ('NDP_CONFIRM_POPIN_TRANSITION', 1, 1, 'Demande de confirmation de la pop-in de transition'),
            ('NDP_STORE_SHOWROOM', 1, 1, 'Boutique/Showroom accessoires'),
            ('NDP_STORE', 1, 1, 'Boutique'),
            ('NDP_URL_WEB_FICHE_ACCESSOIRES', 1, 1, 'URL Web Fiche accessoires'),
            ('NDP_URL_MOB_FICHE_ACCESSOIRES', 1, 1, 'URL Mobile Fiche accessoires'),
            ('NDP_URL_WEB_MOB', 1, 1, 'URL Web et Mobile'),
            ('NDP_URL_WEB_ACCUEIL', 1, 1, 'URL Web Page d\'accueil'),
            ('NDP_URL_WEB_CONNEXION', 1, 1, 'URL Web Page de connexion'),
            ('NDP_URL_MOB_ACCUEIL', 1, 1, 'URL Mobile Page d\'accueil '),
            ('NDP_URL_MOB_CONNEXION', 1, 1, 'URL Mobile Page de connexion'),
            ('NDP_PEUGEOT_WEBSTORE', 1, 1, 'Peugeot webStore'),
            ('NDP_PARCOURS_WEBSTORE', 1, 1, 'Parcours webStore'),
            ('NDP_PARCOURS_PDV', 1, 1, 'Parcours point de vente'),
            ('NDP_PARCOURS_REGIONAL', 1, 1, 'Parcours régional'),
            ('NDP_PARCOURS_PRODUIT', 1, 1, 'Parcours produit'),
            ('NDP_URL_WEB_MOB_WEBSTORE_PRODUITS', 1, 1, 'URL web et mobile Page de résultats'),
            ('NDP_PEUGEOT_SERVICE', 1, 1, 'Peugeot Services'),
            ('NDP_PEUGEOT_ENVIRONNEMENT', 1, 1, 'Peugeot Environnement'),
            ('NDP_PEUGEOT_PRO', 1, 1, 'Peugeot Professionnels'),
            ('NDP_PEUGEOT_WEBSTORE_PRO', 1, 1, 'Peugeot webStore pro'),
            ('NDP_STORE_PRODUIT_DERIVES', 1, 1, 'Boutique Produits dérivés'),
            ('NDP_PEUGEOT_SCOOTER', 1, 1, 'Peugeot Scooters'),
            ('NDP_PEUGEOT_CYCLES', 1, 1, 'Peugeot Cycles'),
            ('NDP_MU_BY_PEUGEOT', 1, 1, 'Mu by Peugeot')
           ");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'URL web' WHERE LABEL_ID ='URL_WEB' AND SITE_ID = 1 AND LANGUE_ID = 1");

        $this->addSql("INSERT INTO `psa_template` (`TEMPLATE_ID`, `TEMPLATE_TYPE_ID`, `TEMPLATE_GROUP_ID`, `TEMPLATE_LABEL`, `TEMPLATE_PATH`, `TEMPLATE_PATH_FO`, `TEMPLATE_COMPLEMENT`, `PLUGIN_ID`) VALUES
            (333, 1, 5, 'NDP_REF_SITE_WS_PSA', 'Ndp_SitesEtWebservicesPSA', NULL, NULL, '')");

        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (234, 333, 4, 0, NULL, NULL, 'NDP_REF_SITE_WS_PSA', NULL, NULL)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (234, 2)");
        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES (234, 3)");
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (2, 234, 2069)');
        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES (13, 234, 2069)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needstables = array('psa_label','psa_label_langue_site');
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 234');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 234');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID = 234');
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID = 333');
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_REF_SITE_WS_PSA",
                "NDP_SITE_DOMAIN_NAME",
                "NDP_CONF_VP",
                "NDP_POPIN_TRANSITION",
                "NDP_CONFIRM_POPIN_TRANSITION",
                "NDP_STORE_SHOWROOM",
                "NDP_STORE",
                "NDP_URL_WEB_FICHE_ACCESSOIRES",
                "NDP_URL_MOB_FICHE_ACCESSOIRES",
                "NDP_URL_WEB_ACCUEIL",
                "NDP_URL_WEB_CONNEXION",
                "NDP_URL_MOB_ACCUEIL",
                "NDP_URL_MOB_CONNEXION",
                "NDP_PEUGEOT_WEBSTORE",
                "NDP_PARCOURS_WEBSTORE",
                "NDP_PARCOURS_PDV",
                "NDP_PARCOURS_REGIONAL",
                "NDP_PARCOURS_PRODUIT",
                "NDP_URL_WEB_MOB_WEBSTORE_PRODUITS",
                "NDP_URL_WEB_MOB",
                "NDP_PEUGEOT_SERVICE",
                "NDP_PEUGEOT_ENVIRONNEMENT",
                "NDP_PEUGEOT_PRO",
                "NDP_PEUGEOT_WEBSTORE_PRO",
                "NDP_STORE_PRODUIT_DERIVES",
                "NDP_PEUGEOT_SCOOTER",
                "NDP_PEUGEOT_CYCLES",
                "NDP_MU_BY_PEUGEOT"
                )
            ');
        }
    }
}
