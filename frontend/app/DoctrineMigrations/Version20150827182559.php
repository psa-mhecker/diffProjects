<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150827182559 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_HT", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_TTC", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_LEGAL_MENTION", NULL, 2, NULL, NULL, NULL, 1)'
        );
        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_HT", 1,  "HT",""),
               ("NDP_TTC", 1,  "TTC",""),
               ("NDP_LEGAL_MENTION", 1,  "Mentions Légales prix comptant","")'
        );

        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
                "NDP_PF23_NOTICE"
             )
        ');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
