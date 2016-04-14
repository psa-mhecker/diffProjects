<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150818182217 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                    ("NDP_CINEMASCOPE", NULL, 2, NULL, NULL, 1, NULL)
        ');

        $this->addSql('REPLACE INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
    ("NDP_CINEMASCOPE", 1, 1, "Visuel cinemascope")
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID IN('NDP_CINEMASCOPE')");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID IN('NDP_CINEMASCOPE')");

    }
}
