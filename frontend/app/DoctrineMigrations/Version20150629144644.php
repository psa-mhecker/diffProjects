<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150629144644 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

      $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_URL_ALREADY_USED", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_URL_REQUIRED", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_URL_ALREADY_USED", 1, 1, "Cette url est déjà utilisée"),
            ("NDP_URL_REQUIRED", 1, 1, "Cette url est obligatoire")'
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
                    "NDP_URL_ALREADY_USED",
                    "NDP_URL_REQUIRED"
                )'
            );
        }
    }
}
