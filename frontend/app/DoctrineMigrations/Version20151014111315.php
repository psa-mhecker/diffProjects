<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151014111315 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // ticket NDP-3551
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'cs' WHERE `LANGUE_ID` = 8;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'da' WHERE `LANGUE_ID` = 9;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'el' WHERE `LANGUE_ID` = 11;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'et' WHERE `LANGUE_ID` = 12;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'ga' WHERE `LANGUE_ID` = 13;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'he' WHERE `LANGUE_ID` = 14;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'hi' WHERE `LANGUE_ID` = 15;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'ja' WHERE `LANGUE_ID` = 20;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'ka' WHERE `LANGUE_ID` = 21;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'ko' WHERE `LANGUE_ID` = 22;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'lo' WHERE `LANGUE_ID` = 23;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'sl' WHERE `LANGUE_ID` = 34;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'sq' WHERE `LANGUE_ID` = 35;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'sv' WHERE `LANGUE_ID` = 37;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'vi' WHERE `LANGUE_ID` = 40;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'wa' WHERE `LANGUE_ID` = 41;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'zh' WHERE `LANGUE_ID` = 42;");


        $this->addSql("REPLACE INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                    ('NDP_MSG_WS_NO_ANSWER_FOR_COUNTRY_LANGUAGE', NULL, 2, NULL, NULL, 1, NULL)
        ");

        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_MSG_WS_NO_ANSWER_FOR_COUNTRY_LANGUAGE', 1, 1, \"Le webservice n'as pas retourné de valeur pour le pays '%country%' et la langue '%language%' \" ),
              ('NDP_MSG_WS_NO_ANSWER_FOR_COUNTRY_LANGUAGE', 2, 1, \"Webservice doesn't return result for  '%country%' and language '%language%' \")
         ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'cz' WHERE `LANGUE_ID` = 8;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'dk' WHERE `LANGUE_ID` = 9;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'gr' WHERE `LANGUE_ID` = 11;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'ee' WHERE `LANGUE_ID` = 12;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'ie' WHERE `LANGUE_ID` = 13;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'il' WHERE `LANGUE_ID` = 14;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'in' WHERE `LANGUE_ID` = 15;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'jp' WHERE `LANGUE_ID` = 20;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'ge' WHERE `LANGUE_ID` = 21;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'kp' WHERE `LANGUE_ID` = 22;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'la' WHERE `LANGUE_ID` = 23;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'si' WHERE `LANGUE_ID` = 34;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'al' WHERE `LANGUE_ID` = 35;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'se' WHERE `LANGUE_ID` = 37;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'vn' WHERE `LANGUE_ID` = 40;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'be' WHERE `LANGUE_ID` = 41;");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_CODE` = 'cn' WHERE `LANGUE_ID` = 42;");
    }
}
