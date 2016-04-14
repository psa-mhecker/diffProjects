<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151209192756 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
            ("NDP_HOME", NULL, 2, NULL, NULL, NULL, 1)
        ');

        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_HOME", 1, "Accueil", "")

        ');

        // supression de la pn7 dans le gabarit showroom
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_ID=791 AND TEMPLATE_PAGE_ID=1015 ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
             "NDP_HOME"
             )
        ');
        }

    }
}
