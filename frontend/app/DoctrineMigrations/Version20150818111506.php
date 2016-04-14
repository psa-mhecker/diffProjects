<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150818111506 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_FO_PATH` = 'Pn15EnteteConfishowStrategy' WHERE `ZONE_ID` = 823");

        $this->addSql("UPDATE psa_label SET LABEL_FO = 1 WHERE LABEL_ID = 'NDP_CHANGE_VEHICLE'");

        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Changer de véhicule' WHERE LABEL_ID = 'NDP_CHANGE_VEHICLE'");

        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_CHANGE_VEHICLE", 1,  "Changer de véhicule","")
               '
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_FO_PATH` = null WHERE `ZONE_ID` = 823");

        $this->addSql("UPDATE psa_label SET LABEL_FO = null WHERE LABEL_ID = 'NDP_CHANGE_VEHICLE'");

        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Changez de véhicule'");

        $this->addSql("DELETE FROM psa_label_langue WHERE LABEL_ID = 'NDP_CHANGE_VEHICLE'");
    }
}
