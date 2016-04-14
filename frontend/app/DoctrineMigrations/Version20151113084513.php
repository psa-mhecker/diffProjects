<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151113084513 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('DELETE FROM psa_label_langue WHERE LABEL_ID="NDP_TTC" ');
        $this->addSql('UPDATE `psa_label` SET  `LABEL_FO` = 1 WHERE LABEL_ID ="NDP_TTC" ');
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_TTC", 1, "TTC", "")');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM psa_label_langue WHERE LABEL_ID="NDP_TTC" ');
        $this->addSql('UPDATE `psa_label` SET  `LABEL_FO` = NULL WHERE LABEL_ID  ="NDP_TTC" ');
    }
}
