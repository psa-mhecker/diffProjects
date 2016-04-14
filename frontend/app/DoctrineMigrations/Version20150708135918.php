<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150708135918 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE psa_zone SET ZONE_FO_PATH="Pf17FormulairesStrategy" WHERE ZONE_ID=837');

        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES ("NDP_PF17_THANKS", NULL, 2, NULL, NULL, NULL, 1) ');
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES ("NDP_PF17_THANKS", 1, "Peugeot vous remercie de la confiance que vous lui accordez.", "") ');
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES ("NDP_PF17_RETURN_TO_HOME", NULL, 2, NULL, NULL, NULL, 1) ');
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES ("NDP_PF17_RETURN_TO_HOME", 1, "Retour à l\'accueil", "") ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE psa_zone SET ZONE_FO_PATH=NULL WHERE ZONE_ID=837');

        $tables = array('psa_label', 'psa_label_langue');

        foreach ($tables as $table) {
            $this->addSql('
                DELETE FROM `'.$table.'` WHERE `LABEL_ID` IN (
                    "NDP_PF17_THANKS",
                    "NDP_PF17_RETURN_TO_HOME"
                 )
            ');
        }
    }
}
