<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401193149 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('DROP TABLE IF EXISTS psa_content_version_cta');

        $this->addSql('CREATE TABLE psa_content_version_cta (PAGE_ZONE_CTA_ID INT NOT NULL, CONTENT_ID INT NOT NULL, LANGUE_ID INT NOT NULL, PAGE_VERSION INT NOT NULL, PAGE_ZONE_CTA_STATUS INT DEFAULT NULL, PAGE_ZONE_CTA_TYPE VARCHAR(50) NOT NULL, PAGE_ZONE_CTA_ORDER INT DEFAULT NULL, PAGE_ZONE_CTA_LABEL VARCHAR(50) DEFAULT NULL, DESCRIPTION LONGTEXT DEFAULT NULL, TARGET VARCHAR(50) NOT NULL, STYLE VARCHAR(50) NOT NULL, CTA_REF_TYPE VARCHAR(50) NOT NULL, CTA_ID INT NOT NULL, PAGE_ID INT NOT NULL, INDEX IDX_A5107385B772F8325622E2C229381310 (CONTENT_ID, LANGUE_ID, PAGE_VERSION), INDEX IDX_A5107385E1DF977A (CTA_ID), INDEX IDX_A5107385B4EDB1E5622E2C2 (PAGE_ID, LANGUE_ID), PRIMARY KEY(PAGE_ZONE_CTA_ID, CONTENT_ID, LANGUE_ID, PAGE_VERSION)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD CONSTRAINT FK_A5107385B772F8325622E2C229381310 FOREIGN KEY (CONTENT_ID, LANGUE_ID, PAGE_VERSION) REFERENCES psa_content_version (CONTENT_ID, LANGUE_ID, CONTENT_VERSION)');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD CONSTRAINT FK_A5107385E1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD CONSTRAINT FK_A5107385B4EDB1E5622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID) REFERENCES psa_page (PAGE_ID, LANGUE_ID)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('DROP TABLE IF EXISTS psa_content_version_cta');

        $this->addSql('CREATE TABLE IF NOT EXISTS `psa_content_version_cta` (
          `ID` int(11) NOT NULL AUTO_INCREMENT,
          `DESCRIPTION` longtext COLLATE utf8_swedish_ci,
          `CONTENT_ID` int(11) DEFAULT NULL,
          `LANGUE_ID` int(11) DEFAULT NULL,
          `PAGE_VERSION` int(11) DEFAULT NULL,
          `CTA_ID` int(11) NOT NULL,
          `PAGE_ID` int(11) DEFAULT NULL,
          `CTA_REF_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
          `TARGET` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
          `STYLE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
          `IS_ACTIVE` tinyint(1) NOT NULL DEFAULT "1",
          `CTA_REF_ORDER` int(11) DEFAULT NULL,
          PRIMARY KEY (`ID`),
          KEY `IDX_A5107385B772F8325622E2C229381310` (`CONTENT_ID`,`LANGUE_ID`,`PAGE_VERSION`),
          KEY `IDX_A5107385E1DF977A` (`CTA_ID`),
          KEY `IDX_A5107385B4EDB1E5622E2C2` (`PAGE_ID`,`LANGUE_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;');

        $this->addSql('ALTER TABLE `psa_content_version_cta`
          ADD CONSTRAINT `FK_A5107385B4EDB1E5622E2C2` FOREIGN KEY (`PAGE_ID`, `LANGUE_ID`) REFERENCES `psa_page` (`PAGE_ID`, `LANGUE_ID`),
          ADD CONSTRAINT `FK_A5107385B772F8325622E2C229381310` FOREIGN KEY (`CONTENT_ID`, `LANGUE_ID`, `PAGE_VERSION`) REFERENCES `psa_content_version` (`CONTENT_ID`, `LANGUE_ID`, `CONTENT_VERSION`),
          ADD CONSTRAINT `FK_A5107385E1DF977A` FOREIGN KEY (`CTA_ID`) REFERENCES `psa_cta` (`ID`);');
    }
}
