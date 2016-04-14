<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151105112610 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_SHOW_MORE', null, 2, null, null, null, 1)
        ");

        $this->addSql("REPLACE INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
              ('NDP_SHOW_MORE', 1, 'Voir plus', null),
              ('NDP_SHOW_MORE', 2, 'See more', null)
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID = 'NDP_SHOW_MORE' AND  LABEL_FO = 1");
        $this->addSql("DELETE FROM psa_label_langue WHERE LABEL_ID = 'NDP_SHOW_MORE'");
    }
}
