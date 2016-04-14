<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150505155039 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // ajout de la fonctionnalite referentiel carselector
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
             (101, 1, 5, 'NDP_REF_CARSELECTORFILTER', 'Ndp_CarSelectorFilter', '', NULL, '')
          ");

        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (117, 101, 4, 0, NULL, NULL, 'NDP_REF_CARSELECTORFILTER', NULL, NULL)
           ");

        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (117, 2)
           ");

        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 117, 2067)
        ');

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_REF_CARSELECTORFILTER', NULL, 2, NULL, NULL, 1, NULL)");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_REF_CARSELECTORFILTER', 1, 1, 'Filtres Car selector')");

        // ajout des ctes de langue
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ("NDP_FILTER_PRICE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_ENERGY", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_GEARBOX_TYPE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_CONSO", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_CLASS", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_SEAT_NB", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_LENGTH", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_WIDTH", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_HEIGHT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME_LVL0", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME_LVL1", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME_LVL2", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME_MAXVALUE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_GAUGE_STEP", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_FILTER_CARSELECTOR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_FILTER_CLASS", NULL, 2, NULL, NULL, 1, NULL)
               ');
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ("NDP_FILTER_PRICE", 1, 1, "Prix"),
                ("NDP_FILTER_ENERGY", 1, 1, "Energie"),
                ("NDP_FILTER_GEARBOX_TYPE", 1, 1, "Types de boite de vitesse"),
                ("NDP_FILTER_CONSO", 1, 1, "Consommation"),
                ("NDP_FILTER_CLASS", 1, 1, "Classe énergétique"),
                ("NDP_FILTER_SEAT_NB", 1, 1, "Nombre de place"),
                ("NDP_FILTER_LENGTH", 1, 1, "Longueur"),
                ("NDP_FILTER_WIDTH", 1, 1, "Largeur"),
                ("NDP_FILTER_HEIGHT", 1, 1, "Hauteur"),
                ("NDP_FILTER_VOLUME", 1, 1, "Volume du coffre"),
                ("NDP_FILTER_VOLUME_LVL0", 1, 1, "Petit"),
                ("NDP_FILTER_VOLUME_LVL1", 1, 1, "Moyen"),
                ("NDP_FILTER_VOLUME_LVL2", 1, 1, "Grand"),
                ("NDP_FILTER_VOLUME_MAXVALUE", 1, 1, "Valeur volume max"),
                ("NDP_GAUGE_STEP", 1, 1, "Pas de la jauge"),
                ("NDP_MSG_FILTER_CARSELECTOR", 1, 1, "Les filtres doivent être paramétrés avant toute activation sur le car selector."),
                ("NDP_MSG_FILTER_CLASS", 1, 1, "Libellés des classes énergétiques (ex : < 150 g/km)")
        ');

        // ajout de la table pour le referentiel filtrecarselector
        $this->addSql('CREATE TABLE psa_carselectorfilter (
              SITE_ID int(11) NOT NULL,
              PRICE_GAUGE float NOT NULL,
              CONSO_GAUGE float NOT NULL,
              LENGTH_GAUGE float NOT NULL,
              WIDTH_GAUGE float NOT NULL,
              HEIGHT_GAUGE float NOT NULL,
              VOLUME_LVL1 int(11) NOT NULL,
              VOLUME_LVL2 int(11) NOT NULL,
              CLASS_A_LABEL varchar(255) NOT NULL,
              CLASS_B_LABEL varchar(255) NOT NULL,
              CLASS_C_LABEL varchar(255) NOT NULL,
              CLASS_D_LABEL varchar(255) NOT NULL,
              CLASS_E_LABEL varchar(255) NOT NULL,
              CLASS_F_LABEL varchar(255) NOT NULL,
              CLASS_G_LABEL varchar(255) NOT NULL,
              PRIMARY KEY (SITE_ID)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // fonctionnalite referentiel carselector
        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID = 117');
        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID = 117');
        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID = 117');
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID =101');
        $this->addSql('DROP TABLE psa_carselectorfilter');

        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_FILTER_PRICE",
                "NDP_FILTER_ENERGY",
                "NDP_FILTER_GEARBOX_TYPE",
                "NDP_FILTER_CONSO",
                "NDP_FILTER_CLASS",
                "NDP_FILTER_SEAT_NB",
                "NDP_FILTER_LENGTH",
                "NDP_FILTER_WIDTH",
                "NDP_FILTER_HEIGHT",
                "NDP_FILTER_VOLUME", "NDP_FILTER_VOLUME_LVL0", "NDP_FILTER_VOLUME_LVL1", "NDP_FILTER_VOLUME_LVL2",
                "NDP_GAUGE_STEP", "NDP_FILTER_VOLUME_MAXVALUE", "NDP_MSG_FILTER_CARSELECTOR", "NDP_MSG_FILTER_CLASS",
                "NDP_REF_CARSELECTORFILTER"
                )
            ');
        }

    }
}
