<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150623141119 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_LIAISON_ID", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_LIAISON_ID", 1, 1, "Id de la liaison")'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
              'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_LIAISON_ID"
                )'
            );
        }
    }
}
