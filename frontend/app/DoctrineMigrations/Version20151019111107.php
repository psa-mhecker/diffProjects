<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151019111107 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
('NDP_MOBILE_DISPLAY_MODE_INFO', 1, 1, 'Choix de l''affichage des colonnes en mode slider ou bien les unes à la suite des autres.')");

        $this->addSql("INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
('NDP_MOBILE_DISPLAY_MODE_INFO', NULL, 2, NULL, NULL, 1, NULL)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_label_langue_site` WHERE `LABEL_ID` = 'NDP_MOBILE_DISPLAY_MODE_INFO' AND `LANGUE_ID` = 1 AND `SITE_ID` = 1");

        $this->addSql("DELETE FROM `psa_label` WHERE `LABEL_ID` = 'NDP_MOBILE_DISPLAY_MODE_INFO'");
    }
}
