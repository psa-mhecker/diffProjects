<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150727153501 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_FO_PATH` = 'Pc84CatalogueApplicationsStrategy' WHERE `ZONE_ID` = 821");

        // Pour ne pas avoir de probleme de collation different entre les donnees
        $this->addSql('
            SET FOREIGN_KEY_CHECKS = 0;
            ALTER TABLE `psa_model` CONVERT TO CHARACTER SET utf8 COLLATE utf8_Swedish_ci;
            ALTER TABLE `psa_model_config` CONVERT TO CHARACTER SET utf8 COLLATE utf8_Swedish_ci;
            ALTER TABLE `psa_model_site` CONVERT TO CHARACTER SET utf8 COLLATE utf8_Swedish_ci;
            ALTER TABLE `psa_model_view_angle` CONVERT TO CHARACTER SET utf8 COLLATE utf8_Swedish_ci;
            SET FOREIGN_KEY_CHECKS = 1;
        ');

        $this->addSql('INSERT INTO psa_label
            (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_COMPATIBLE_VEHICLES", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_DOWNLOAD_APPLICATION", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE", NULL, 2, NULL, NULL, NULL, 1)
            ');

        $this->addSql('INSERT INTO `psa_label_langue`
            (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_COMPATIBLE_VEHICLES", 1, "Véhicules compatibles", ""),
            ("NDP_DOWNLOAD_APPLICATION", 1, "télécharger l\'application", ""),
            ("NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES", 1, "Compatible exclusivement avec les <br> véhicules suivants", ""),
            ("NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE", 1, "Mon véhicule possède-t-il ce service ?", "")
            ');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_FO_PATH` = null WHERE `ZONE_ID` = 821");

        $tables = array('psa_label', 'psa_label_langue');

        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_COMPATIBLE_VEHICLES",
                    "NDP_DOWNLOAD_APPLICATION",
                    "NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES",
                    "NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE"
                )'
            );
        }
    }
}
