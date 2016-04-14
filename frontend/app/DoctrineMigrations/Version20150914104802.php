<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150914104802 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                        ("NDP_3_VISUELS_3_WEB", NULL, 2, NULL, NULL, 1, NULL);
        ');

        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                        ("NDP_3_VISUELS_3_WEB", 1, 1, "3 visuels web du type \"trois visuels\" sont nécessaires.");
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM `psa_label` WHERE `LABEL_ID` = "NDP_3_VISUELS_3_WEB";');

        $this->addSql('DELETE FROM `psa_label_langue_site` WHERE `LABEL_ID` = "NDP_3_VISUELS_3_WEB";');
    }
}
