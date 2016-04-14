<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150622185230 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("IAB_BILLBOARD", NULL, 2, NULL, NULL, 1, NULL),
            ("IAB_BILLBOARD_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
            ("IAB_PAVE", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("IAB_BILLBOARD", 1, 1, "IAB billboard"),
            ("IAB_BILLBOARD_MOBILE", 1, 1, "IAB billboard"),
            ("IAB_PAVE", 1, 1, "IAB Pavé")'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "IAB_BILLBOARD","IAB_BILLBOARD_MOBILE","IAB_PAVE"
                )'
            );
        }
    }
}
