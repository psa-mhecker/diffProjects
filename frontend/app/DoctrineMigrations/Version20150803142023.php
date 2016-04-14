<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150803142023 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("DELETE FROM `psa_label_langue_site` WHERE LABEL_ID = 'NDP_CHOOSE_LANGUAGE'");

        $this->addSql("REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
                    ('NDP_CHOOSE_LANGUAGE', 1, 'Langue', ''),
                    ('NDP_CHOOSE_LANGUAGE', 2, 'Language', ''),
                    ('NDP_CHOOSE_LANGUAGE', 4, 'Langue', ''),
                    ('NDP_CHOOSE_LANGUAGE', 10, 'Sprache', '')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
