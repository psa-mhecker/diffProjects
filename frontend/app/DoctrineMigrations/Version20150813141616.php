<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150813141616 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("drop table if exists psa_contenu_attribut");
        $this->addSql('CREATE TABLE IF NOT EXISTS `psa_content_version_attribut` (
            `CONTENT_ID` int(11) NOT NULL,
            `CONTENT_VERSION` int(11) NOT NULL,
            `LANGUE_ID` int(11) NOT NULL DEFAULT "1",
            `CONTENT_ATTRIBUT_NAME` varchar(64) COLLATE utf8_swedish_ci DEFAULT NULL,
            `CONTENT_ATTRIBUT_STRING` varchar(254) COLLATE utf8_swedish_ci DEFAULT NULL,
            `CONTENT_ATTRIBUT_DATE` date DEFAULT NULL,
            `CONTENT_ATTRIBUT_DATETIME` datetime DEFAULT NULL,
            `CONTENT_ATTRIBUT_TEXT` text COLLATE utf8_swedish_ci,
            `CONTENT_ATTRIBUT_INTEGER` int(11) DEFAULT NULL,
            `CONTENT_ATTRIBUT_LONG` bigint(20) DEFAULT NULL,
            `CONTENT_ATTRIBUT_BOOLEAN` tinyint(1) DEFAULT NULL,
            `CONTENT_ATTRIBUT_DECIMAL` decimal(10,0) DEFAULT NULL,
            `CONTENT_ATTRIBUT_TIME` time DEFAULT NULL,
            `CONTENT_ATTRIBUT_MONNAIE` float(8,2) DEFAULT NULL,
            `CONTENT_ATTRIBUT_REEL` float DEFAULT NULL,
            `CONTENT_ATTRIBUT_OCTECT` tinyint(4) DEFAULT NULL,
            `MEDIA_ID` int(11) DEFAULT NULL,
            PRIMARY KEY (`CONTENT_ID`,`CONTENT_VERSION`,`LANGUE_ID`,`CONTENT_ATTRIBUT_NAME`),
            KEY `I_CONTENT_ATTRIBUT_10` (`MEDIA_ID`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;');


        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
            (700, 3, 2, 'NDP_CNT_PROMOTION', 'Cms_Content_Ndp_Pc76Promotion', NULL, NULL, '')
            ");

        $this->addSql("INSERT INTO psa_content_type (CONTENT_TYPE_ID, TEMPLATE_ID, CONTENT_TYPE_LABEL, CONTENT_TYPE_COMPLEMENT, CONTENT_TYPE_ADMINISTRATION, CONTENT_TYPE_PAGE, CONTENT_TYPE_DEFAULT, CONTENT_TYPE_PLUGIN) VALUES
            (8, 700, 'NDP_CNT_PROMOTION', NULL, 0, NULL, NULL, 0)
            ");

        $this->addSql("INSERT INTO psa_content_type_site (CONTENT_TYPE_ID, SITE_ID, CONTENT_TYPE_SITE_EMISSION, CONTENT_TYPE_SITE_RECEPTION, CONTENT_ALERTE, CONTENT_ALERTE_URL) VALUES
            (8, 2, NULL, NULL, NULL, NULL)
            ");

        $this->addSql("INSERT INTO psa_user_role (USER_LOGIN, ROLE_ID, CONTENT_TYPE_ID, SITE_ID) VALUES
            ('admin', 7, 8, 2)
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("drop table if exists psa_content_version_attribut");

        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS `psa_content_attribut` (
            `CONTENT_ATTRIBUT_ID` int(11) NOT NULL AUTO_INCREMENT,
            `CONTENT_VERSION_ID` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
            `CONTENT_ATTRIBUT_NAME` varchar(256) COLLATE utf8_swedish_ci DEFAULT NULL,
            `CONTENT_ATTRIBUT_STRING` varchar(254) COLLATE utf8_swedish_ci DEFAULT NULL,
            `CONTENT_ATTRIBUT_DATE` date DEFAULT NULL,
            `CONTENT_ATTRIBUT_DATETIME` datetime DEFAULT NULL,
            `CONTENT_ATTRIBUT_TEXT` text COLLATE utf8_swedish_ci,
            `CONTENT_ATTRIBUT_INTEGER` int(11) DEFAULT NULL,
            `CONTENT_ATTRIBUT_LONG` bigint(20) DEFAULT NULL,
            `CONTENT_ATTRIBUT_BOOLEAN` tinyint(1) DEFAULT NULL,
            `CONTENT_ATTRIBUT_DECIMAL` decimal(10,0) DEFAULT NULL,
            `CONTENT_ATTRIBUT_TIME` time DEFAULT NULL,
            `CONTENT_ATTRIBUT_MONNAIE` float(8,2) DEFAULT NULL,
            `CONTENT_ATTRIBUT_REEL` float DEFAULT NULL,
            `CONTENT_ATTRIBUT_OCTECT` tinyint(4) DEFAULT NULL,
            `MEDIA_ID` int(11) DEFAULT NULL,
            PRIMARY KEY (`CONTENT_ATTRIBUT_ID`),
            KEY `I_CONTENT_ATTRIBUT_10` (`MEDIA_ID`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;');

        
        $this->addSql("DELETE FROM psa_content WHERE CONTENT_TYPE_ID='8'");
        $this->addSql("DELETE FROM psa_content_type_site WHERE CONTENT_TYPE_ID='8'");
        $this->addSql("DELETE FROM psa_user_role WHERE CONTENT_TYPE_ID='8'");
        $this->addSql("DELETE FROM psa_content_type WHERE CONTENT_TYPE_ID='8'");
        $this->addSql("DELETE FROM psa_template WHERE TEMPLATE_ID='700'");
    }
}
