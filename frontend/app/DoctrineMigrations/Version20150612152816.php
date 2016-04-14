<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150612152816 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
        ("NDP_OR", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_MORE_FILTER", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_FILTER_BY", NULL, 2, NULL, NULL, NULL, 1)
        ');

        $this->addSql('INSERT INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
        ("NDP_OR", 1, "ou", ""),
        ("NDP_MORE_FILTER", 1, "Plus de filtres", ""),
        ("NDP_FILTER_BY", 1, "filtrer par", "")
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                 "NDP_OR",
                 "NDP_MORE_FILTER",
                 "NDP_FILTER_BY"
                 )
                '
            );
        }

    }
}
