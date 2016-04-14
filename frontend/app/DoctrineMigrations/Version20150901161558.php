<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150901161558 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_MSG_LEGAL_CONSO", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_MSG_COMPL_INFO", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_MSG_FINANCEMENT_INFO", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_UNIT_FUEL", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_UNIT_BY_FUEL", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_UNIT_CO2", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_UNIT_BY_CO2", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_OR", NULL, 2, NULL, NULL, NULL, 1)
              ');

        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_MSG_LEGAL_CONSO", 1,  "MENTION LEGALES DE LA CONSOMMATIONS A TRADUIRE - NDP_MSG_LEGAL_CONSO. ",""),
               ("NDP_MSG_COMPL_INFO", 1,  "Message complémentare d\'information à traduire - NDP_MSG_COMPL_INFO. ",""),
               ("NDP_MSG_FINANCEMENT_INFO", 1,  "Un crédit vous engage et doit être remboursé. Vérifiez vos capacités de remboursement avant de vous engager",""),
               ("NDP_UNIT_FUEL", 1,  "l",""),
               ("NDP_UNIT_BY_FUEL", 1,  "100km",""),
               ("NDP_UNIT_CO2", 1,  "g",""),
               ("NDP_UNIT_BY_CO2", 1,  "CO2/km",""),
               ("NDP_OR", 1,  "ou","")
               ');
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
                    "NDP_MSG_LEGAL_CONSO",
                    "NDP_MSG_COMPL_INFO",
                    "NDP_MSG_FINANCEMENT_INFO",
                    "NDP_UNIT_FUEL",
                    "NDP_UNIT_BY_FUEL",
                    "NDP_UNIT_CO2",
                    "NDP_UNIT_BY_CO2",
                    "NDP_OR"
                )'
            );
        }
    }
}
