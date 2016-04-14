<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150624093830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("1/3_ACTU_UNIQUE", NULL, 2, NULL, NULL, 1, NULL),
            ("1/3_ACTU_UNIQUE_MOBILE", NULL, 2, NULL, NULL, 1, NULL)
            '
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("1/3_ACTU_UNIQUE", 1, 1, "1/3 ou Entête petit visuel ou Actualité visuel unique"),
            ("1/3_ACTU_UNIQUE_MOBILE", 1, 1, "1/3 ou Actualité Mobile")
            '
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
                    "1/3_ACTU_UNIQUE","1/3_ACTU_UNIQUE_MOBILE"
                )'
            );
        }
    }
}
