<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150519105421 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
              "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_NOMBRE_TRANCHE'
                 )
                "
            );
        }
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_ADD_SLICE_TOGGLE', NULL, 2, NULL, NULL, 1, NULL)
                 ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_ADD_SLICE_TOGGLE', 1, 1, 'Ajouter une tranche dans le toggle')
                ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
              "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_ADD_SLICE_TOGGLE'
                 )
                "
            );
        }
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_NOMBRE_TRANCHE', NULL, 2, NULL, NULL, 1, NULL)
                 ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_NOMBRE_TRANCHE', 1, 1, 'Nombre de tranche')
                ");
    }
}
