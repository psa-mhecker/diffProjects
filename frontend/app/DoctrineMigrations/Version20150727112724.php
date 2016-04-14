<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150727112724 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_PC58_CALL_US", NULL, 2, NULL, NULL, NULL, 1)
            ;'
        );

        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_PC58_CALL_US", 1, "appelez-nous : ", "")
            ;'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_PC58_CALL_US"
                )'
            );
        }
    }
}
