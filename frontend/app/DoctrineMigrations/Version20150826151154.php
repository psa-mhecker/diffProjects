<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150826151154 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_PF53-58_ERROR_TEXT", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_DISCOVER_SEVERAL_MOTOR", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_CHOOSE_TRANSMISION_TYPE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_CHOICE_TRANSMISSION_TYPE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_NO_PREFERENCE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_MANUAL_TRANSMISSION", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_AUTOMATED_TRANSMISSION", NULL, 2, NULL, NULL, NULL, 1)
              '
        );

        $this->addSql("UPDATE psa_label SET LABEL_FO = 1 WHERE LABEL_ID ='NDP_CASH_PRICE'");
        $this->addSql("UPDATE psa_label SET LABEL_FO = 1 WHERE LABEL_ID ='NDP_MONTHLY_PRICE'");

        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_PF53-58_ERROR_TEXT", 1,  "La configuration ou le modèle que vous avez demandé n\'est plus disponible",""),
               ("NDP_DISCOVER_SEVERAL_MOTOR", 1,  "Découvrez les %nbMotor% moteurs disponibles",""),
               ("NDP_CASH_PRICE", 1,  "Prix comptant",""),
               ("NDP_MONTHLY_PRICE", 1,  "Prix mensuel",""),
               ("NDP_CHOOSE_TRANSMISION_TYPE", 1,  "Choisissez une boite de vitesses",""),
               ("NDP_CHOICE_TRANSMISSION_TYPE", 1,  "Choix du type de boite de vitesses",""),
               ("NDP_NO_PREFERENCE", 1,  "Pas de préférence",""),
               ("NDP_MANUAL_TRANSMISSION", 1,  "Manuelle",""),
               ("NDP_AUTOMATED_TRANSMISSION", 1,  "Automatique","")
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
                    "NDP_PF53-58_ERROR_TEXT",
                    "NDP_DISCOVER_SEVERAL_MOTOR",
                    "NDP_CHOOSE_TRANSMISION_TYPE",
                    "NDP_CHOICE_TRANSMISSION_TYPE",
                    "NDP_NO_PREFERENCE",
                    "NDP_MANUAL_TRANSMISSION",
                    "NDP_AUTOMATED_TRANSMISSION"
                )'
            );
        }

        $this->addSql("UPDATE psa_label SET LABEL_FO = NULL WHERE LABEL_ID ='NDP_CASH_PRICE'");
        $this->addSql("UPDATE psa_label SET LABEL_FO = NULL WHERE LABEL_ID ='NDP_MONTHLY_PRICE'");
        $this->addSql("DELETE FROM psa_label_langue WHERE LABEL_ID ='NDP_CASH_PRICE'");
        $this->addSql("DELETE FROM psa_label_langue WHERE LABEL_ID ='NDP_MONTHLY_PRICE'");
    }
}
