<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150818110540 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET  ZONE_FO_PATH ='PC95InterestedByStrategy' WHERE ZONE_ID=835" );
        $this->addSql('REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                    ("NDP_CHANGE_VEHICLE", NULL, 2, NULL, NULL, 1, NULL)
        ');

        $this->addSql('REPLACE INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
    ("NDP_CHANGE_VEHICLE", 1, 1, "Changez de véhicule")
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID IN('NDP_CHANGE_VEHICLE')");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID IN('NDP_CHANGE_VEHICLE')");

    }
}
