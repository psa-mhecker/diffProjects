<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150803163744 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_CARACTERISTIQUES", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_FINITION_AVAILABLE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_GEAR", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ENERGY", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_DISCOVER_SEVERAL", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SEVERAL_MOTORS", NULL, 2, NULL, NULL, NULL, 1)

            '
        );

        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_CARACTERISTIQUES", 1, "caractéristiques", ""),
            ("NDP_FINITION_AVAILABLE", 1, "finitions disponible", ""),
            ("NDP_GEAR", 1, "boite de vitesse", ""),
            ("NDP_ENERGY", 1, "energie", ""),
            ("NDP_DISCOVER_SEVERAL", 1, "Découvrez les", ""),
            ("NDP_SEVERAL_MOTORS", 1, "moteurs disponible", "")


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
                    "NDP_CARACTERISTIQUES",
                    "NDP_FINITION_AVAILABLE",
                    "NDP_GEAR",
                    "NDP_ENERGY",
                    "NDP_DISCOVER_SEVERAL",
                    "NDP_SEVERAL_MOTORS"
                )'
            );
        }
    }
}
