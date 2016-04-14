<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150730155548 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = 1 WHERE LABEL_ID IN (
                "NDP_NEW",
                "NDP_SPECIAL_OFFER",
                "NDP_SPECIAL_SERIE"
            )'
        );



        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_FIND_DEALER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CHOOSE_DEALER_NAME", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_YOUR_DEALER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_KM", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ENJOY_THIS_OFFER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SEE_AVAILABLE_STOCKS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_LINING", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CONSUMPTION2", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_EMISSION", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_EITHER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_EURO", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SAVING", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_RECOMMENDED_PRICE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_DAY", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_DAYS", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_AVAILABLE_IN", NULL, 2, NULL, NULL, NULL, 1)
            ;'
        );



        $this->addSql('INSERT INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
            ("NDP_FIND_DEALER", 1, "Trouver un point de vente", ""),
            ("NDP_CHOOSE_DEALER_NAME", 1, "Indiquez le nom du point de vente", ""),
            ("NDP_YOUR_DEALER", 1, "Votre point de vente", ""),
            ("NDP_KM", 1, "km", ""),
            ("NDP_ENJOY_THIS_OFFER", 1, "Profitez de cette offre", ""),
            ("NDP_SEE_AVAILABLE_STOCKS", 1, "Voir les stocks disponibles", ""),
            ("NDP_LINING", 1, "Garnissage", ""),
            ("NDP_CONSUMPTION2", 1, "Consommation", ""),
            ("NDP_EMISSION", 1, "Emission de Co2", ""),
            ("NDP_EITHER", 1, "soit", ""),
            ("NDP_EURO", 1, "&euro;", ""),
            ("NDP_SAVING", 1, "d\'économie", ""),
            ("NDP_RECOMMENDED_PRICE", 1, "Prix conseillé", ""),
            ("NDP_DAY", 1, "jour", ""),
            ("NDP_DAYS", 1, "jours", ""),
            ("NDP_AVAILABLE_IN", 1, "Disponible sous ", "")
            ;'
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = NULL WHERE LABEL_ID IN (
                    "NDP_NEW",
                    "NDP_SPECIAL_OFFER",
                    "NDP_SPECIAL_SERIE"
                )'
        );

        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_FIND_DEALER",
                    "NDP_CHOOSE_DEALER_NAME",
                    "NDP_YOUR_DEALER",
                    "NDP_KM",
                    "NDP_ENJOY_THIS_OFFER",
                    "NDP_SEE_AVAILABLE_STOCKS",
                    "NDP_LINING",
                    "NDP_CONSUMPTION2",
                    "NDP_EMISSION",
                    "NDP_EITHER",
                    "NDP_EURO",
                    "NDP_SAVING",
                    "NDP_RECOMMENDED_PRICE",
                    "NDP_DAY",
                    "NDP_DAYS",
                    "NDP_AVAILABLE_IN"
                )'
            );
        }
    }
}
