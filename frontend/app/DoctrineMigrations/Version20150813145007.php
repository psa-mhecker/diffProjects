<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150813145007 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
    ("NDP_JE_VEUX", NULL, 2, NULL, NULL, NULL, 1)'
        );

        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
    ("NDP_JE_VEUX", 1,  "JE VEUX","")'
        );

    }
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

    }
}
