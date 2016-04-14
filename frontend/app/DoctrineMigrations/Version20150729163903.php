<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150729163903 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_PRICE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CASH", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_MONTHLY", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CONFIGURE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CLOSED", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SELECT_FINISH", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_VEHICLE_CONFIGURATION_START", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_BACK_TO_SHOWROOM", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CONTINUE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_VEHICLE_CONFIGURATION_REDIRECT", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_NOT_REDIRECT", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SECOND", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SECONDS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_VEHICLE_CONFIGURATION_ACCESS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_GASOLINE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_DIESEL", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_DISCOVER_ONE_FINISH", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_DISCOVER_SEVERAL_FINISH", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ZOOM", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ADD_TO_COMPARATOR", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_OTHER_TO_COMPARATOR", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ALL_SELECTED", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_MORE_DETAILS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_FROM", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_INCLUDE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PRICE_AND_ENGINE_AVAILABLE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_EQUIPMENTS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ENGINE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_TOTAL_CO2", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ENERGY_CLASS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CONSUMPTION", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_POWER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_FINISH", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SEVERAL_MOTOR_AVAILABLE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ONE_MOTOR_AVAILABLE", NULL, 2, NULL, NULL, NULL, 1)
            '
        );

        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_PRICE", 1, "Prix", ""),
            ("NDP_CASH", 1, "comptant", ""),
            ("NDP_MONTHLY", 1, "mensuel", ""),
            ("NDP_CONFIGURE", 1, "Configurer", ""),
            ("NDP_CLOSED", 1, "Fermé", ""),
            ("NDP_SELECT_FINISH", 1, "Sélectionnez une finition", ""),
            ("NDP_VEHICLE_CONFIGURATION_START", 1, "Vous commencez la configuration de votre véhicule", ""),
            ("NDP_BACK_TO_SHOWROOM", 1, "Non je souhaite retourner au showroom", ""),
            ("NDP_CONTINUE", 1, "Continuer", ""),
            ("NDP_VEHICLE_CONFIGURATION_REDIRECT", 1, "Vous allez être redirigé vers la configuration de votre véhicule dans", ""),
            ("NDP_NOT_REDIRECT", 1, "Si vous n\'êtes pas redirigé veuillez cliquer ici", ""),
            ("NDP_SECOND", 1, "seconde", ""),
            ("NDP_SECONDS", 1, "secondes", ""),
            ("NDP_VEHICLE_CONFIGURATION_ACCESS", 1, "Accès à la configuration de votre véhicule", ""),
            ("NDP_GASOLINE", 1, "Essence", ""),
            ("NDP_DIESEL", 1, "Diesel", ""),
            ("NDP_DISCOVER_ONE_FINISH", 1, "Découvrez la Finition Disponible", ""),
            ("NDP_DISCOVER_SEVERAL_FINISH", 1, "Découvrez les %nbFinition% Finitions Disponibles", ""),
            ("NDP_ZOOM", 1, "Zoom", ""),
            ("NDP_ADD_TO_COMPARATOR", 1, "Ajouter au comparateur", ""),
            ("NDP_OTHER_TO_COMPARATOR", 1, "Sélectionner une autre finition", ""),
            ("NDP_ALL_SELECTED", 1, "Vous ne pouvez pas choisir plus de 3 finitions", ""),
            ("NDP_MORE_DETAILS", 1, "Plus de détails", ""),
            ("NDP_FROM", 1, "A partir de", ""),
            ("NDP_INCLUDE", 1, "inclus", ""),
            ("NDP_PRICE_AND_ENGINE_AVAILABLE", 1, "Prix & motorisations disponibles", ""),
            ("NDP_EQUIPMENTS", 1, "Equipements", ""),
            ("NDP_ENGINE", 1, "Motorisation", ""),
            ("NDP_TOTAL_CO2", 1, "Total émission de CO2 (g/km)", ""),
            ("NDP_ENERGY_CLASS", 1, "Classe energetique", ""),
            ("NDP_CONSUMPTION", 1, "Consommation (l/100km)", ""),
            ("NDP_POWER", 1, "Puissance (kw)", ""),
            ("NDP_FINISH", 1, "Finition", ""),
            ("NDP_SEVERAL_MOTOR_AVAILABLE", 1, "avec le moteur %motorName% <br> disponible en  %motorNumber% motorisation.", ""),
            ("NDP_ONE_MOTOR_AVAILABLE", 1, "avec le moteur %motorName% <br> disponible en  %motorNumber% motorisations.", "")
            '
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_PRICE",
                    "NDP_CASH",
                    "NDP_MONTHLY",
                    "NDP_CONFIGURE",
                    "NDP_CLOSED",
                    "NDP_SELECT_FINISH",
                    "NDP_VEHICLE_CONFIGURATION_START",
                    "NDP_BACK_TO_SHOWROOM",
                    "NDP_CONTINUE",
                    "NDP_VEHICLE_CONFIGURATION_REDIRECT",
                    "NDP_NOT_REDIRECT",
                    "NDP_SECOND",
                    "NDP_SECONDS",
                    "NDP_VEHICLE_CONFIGURATION_ACCESS",
                    "NDP_GASOLINE",
                    "NDP_DIESEL",
                    "NDP_DISCOVER_ONE_FINISH",
                    "NDP_DISCOVER_SEVERAL_FINISH",
                    "NDP_ZOOM",
                    "NDP_ADD_TO_COMPARATOR",
                    "NDP_OTHER_TO_COMPARATOR",
                    "NDP_ALL_SELECTED",
                    "NDP_MORE_DETAILS",
                    "NDP_FROM",
                    "NDP_INCLUDE",
                    "NDP_PRICE_AND_ENGINE_AVAILABLE",
                    "NDP_EQUIPMENTS",
                    "NDP_ENGINE",
                    "NDP_TOTAL_CO2",
                    "NDP_ENERGY_CLASS",
                    "NDP_CONSUMPTION",
                    "NDP_POWER",
                    "NDP_FINISH",
                    "NDP_SEVERAL_MOTOR_AVAILABLE",
                    "NDP_ONE_MOTOR_AVAILABLE"
                )'
            );
        }
    }
}
