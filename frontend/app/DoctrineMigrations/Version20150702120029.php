<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150702120029 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
            ("NDP_PEUGEOT", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SEARCH", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_MENU", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ALL_PEUGEOT", NULL, 2, NULL, NULL, NULL, 1)
            ');
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_PEUGEOT", 1, "PEUGEOT", ""),
            ("NDP_SEARCH", 1, "RECHERCHER", ""),
            ("NDP_MENU", 1, "MENU", ""),
            ("NDP_ALL_PEUGEOT", 1, "TOUT PEUGEOT", "")
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
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                "NDP_PEUGEOT",
                "NDP_SEARCH",
                "NDP_MENU",
                "NDP_ALL_PEUGEOT"
                 )'
            );
        }
    }
}
