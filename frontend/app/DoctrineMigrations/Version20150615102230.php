<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150615102230 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_MSG_PRIORISATION_CAT', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LABEL_PRIORISATION', NULL, 2, NULL, NULL, 1, NULL)

                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_MSG_PRIORISATION_CAT', 1, 1, 'Cette priorisation est utilisée pour l’affichage des catégories sur la Range Bar.'),
                ('NDP_LABEL_PRIORISATION', 1, 1, 'Catégories à prioriser' )

                ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $tables = array('psa_label', 'psa_label_langue_site');
            foreach ($tables as $table) {
                $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN (
                "NDP_MSG_PRIORISATION_CAT",
                "NDP_LABEL_PRIORISATION"
             )');
            }
    }
}
