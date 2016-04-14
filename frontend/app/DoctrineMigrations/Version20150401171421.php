<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401171421 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE IF EXISTS psa_page_zone_cta');

        $this->addSql('CREATE TABLE psa_page_zone_cta (PAGE_ZONE_CTA_ID INT NOT NULL, PAGE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, PAGE_VERSION INT NOT NULL, ZONE_TEMPLATE_ID INT NOT NULL, PAGE_ZONE_CTA_STATUS INT DEFAULT NULL, PAGE_ZONE_CTA_TYPE VARCHAR(50) NOT NULL, PAGE_ZONE_CTA_ORDER INT DEFAULT NULL, PAGE_ZONE_CTA_LABEL VARCHAR(50) DEFAULT NULL, DESCRIPTION LONGTEXT DEFAULT NULL, TARGET VARCHAR(50) NOT NULL, STYLE VARCHAR(50) NOT NULL, CTA_REF_TYPE VARCHAR(50) NOT NULL, CTA_ID INT NOT NULL, INDEX IDX_B03DE77CB4EDB1E5622E2C229381310F15EAE15 (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID), INDEX IDX_B03DE77CE1DF977A (CTA_ID), INDEX IDX_B03DE77CB4EDB1E5622E2C2 (PAGE_ID, LANGUE_ID), PRIMARY KEY(PAGE_ZONE_CTA_ID, PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CB4EDB1E5622E2C229381310F15EAE15 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID) REFERENCES psa_page_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID)');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CB4EDB1E5622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID) REFERENCES psa_page (PAGE_ID, LANGUE_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE IF EXISTS psa_page_zone_cta');

        $this->addSql('CREATE TABLE IF NOT EXISTS `psa_page_zone_cta` (
           `ID` int(11) NOT NULL AUTO_INCREMENT,
           `PAGE_ID` int(11) DEFAULT NULL,
           `PAGE_VERSION` int(11) DEFAULT NULL,
           `ZONE_TEMPLATE_ID` int(11) DEFAULT NULL,
           `LANGUE_ID` int(11) DEFAULT NULL,
           `CTA_ID` int(11) NOT NULL,
           `DESCRIPTION` longtext COLLATE utf8_swedish_ci,
           `CTA_REF_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
           `TARGET` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
           `STYLE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
           `IS_ACTIVE` tinyint(1) NOT NULL DEFAULT "1",
           `CTA_REF_ORDER` int(11) DEFAULT NULL,
           PRIMARY KEY (`ID`),
           KEY `IDX_B03DE77CB4EDB1E29381310F15EAE155622E2C2`
        (`PAGE_ID`,`PAGE_VERSION`,`ZONE_TEMPLATE_ID`,`LANGUE_ID`),
           KEY `IDX_B03DE77CE1DF977A` (`CTA_ID`),
           KEY `IDX_B03DE77CB4EDB1E5622E2C2` (`PAGE_ID`,`LANGUE_ID`),
           KEY `FK_B03DE77CB4EDB1E29381310F15EAE155622E2C2`
        (`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`ZONE_TEMPLATE_ID`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci');

        $this->addSql('ALTER TABLE `psa_page_zone_cta`
               ADD CONSTRAINT `FK_B03DE77CB4EDB1E29381310F15EAE155622E2C2` FOREIGN
            KEY (`PAGE_ID`, `LANGUE_ID`, `PAGE_VERSION`, `ZONE_TEMPLATE_ID`)
            REFERENCES `psa_page_zone` (`PAGE_ID`, `LANGUE_ID`, `PAGE_VERSION`,
            `ZONE_TEMPLATE_ID`),
               ADD CONSTRAINT `FK_B03DE77CB4EDB1E5622E2C2` FOREIGN KEY (`PAGE_ID`,
            `LANGUE_ID`) REFERENCES `psa_page` (`PAGE_ID`, `LANGUE_ID`),
               ADD CONSTRAINT `FK_B03DE77CE1DF977A` FOREIGN KEY (`CTA_ID`)
            REFERENCES `psa_cta` (`ID`);');

    }
}
