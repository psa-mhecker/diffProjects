<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150910173619 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_4_SOCIAL_MAX', null, 2, null, null, 1, null)
              ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_4_SOCIAL_MAX', 1, 1, 'Vous ne pouvez pas sélectionner plus de 4 réseaux sociaux')
              ");
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
                    "NDP_4_SOCIAL_MAX"
                )'
            );
        }
    }
}
