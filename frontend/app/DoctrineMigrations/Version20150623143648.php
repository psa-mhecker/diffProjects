<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150623143648 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE IF EXISTS psa_page_multi_zone_multi_cta_cta');
        $this->addSql('DROP TABLE IF EXISTS psa_page_multi_zone_multi_cta');
        $this->addSql('CREATE TABLE IF NOT EXISTS `psa_page_multi_zone_multi_cta` (
            `PAGE_ZONE_CTA_ID` int(11) NOT NULL,
            `PAGE_ID` int(11) NOT NULL,
            `LANGUE_ID` int(11) NOT NULL,
            `PAGE_VERSION` int(11) NOT NULL,
            `AREA_ID` int(11) NOT NULL,
            `PAGE_ZONE_MULTI_ID` int(11) NOT NULL,
            `PAGE_ZONE_MULTI_TYPE` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
            `PAGE_ZONE_MULTI_ORDER` int(11) DEFAULT NULL,
            `PAGE_ZONE_CTA_STATUS` int(11) DEFAULT NULL,
            `PAGE_ZONE_CTA_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
            `PAGE_ZONE_CTA_ORDER` int(11) DEFAULT NULL,
            `PAGE_ZONE_CTA_LABEL` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
            `DESCRIPTION` longtext COLLATE utf8_swedish_ci,
            `TARGET` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
            `STYLE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
            `CTA_REF_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
            `CTA_ID` int(11) DEFAULT NULL,
            PRIMARY KEY (`PAGE_ZONE_CTA_ID`,`PAGE_ZONE_CTA_TYPE`,`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
            KEY `IDX_59FECE2CB4EDB1E5622E2C229381310F15EAE15F55F4D23FD9DFAC6` (`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
            KEY `IDX_59FECE2CE1DF977A` (`CTA_ID`),
            KEY `IDX_59FECE2CB4EDB1E5622E2C2` (`PAGE_ID`,`LANGUE_ID`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;');
         
        $this->addSql('
            CREATE TABLE IF NOT EXISTS `psa_page_multi_zone_multi_cta_cta` (
              `PAGE_ZONE_CTA_CTA_ID` int(11) NOT NULL,
              `PAGE_ZONE_CTA_ID` int(11) NOT NULL,
              `PAGE_ID` int(11) NOT NULL,
              `LANGUE_ID` int(11) NOT NULL,
              `PAGE_VERSION` int(11) NOT NULL,
              `AREA_ID` int(11) NOT NULL,
              `PAGE_ZONE_MULTI_ID` int(11) NOT NULL,
              `PAGE_ZONE_MULTI_TYPE` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
              `PAGE_ZONE_MULTI_ORDER` int(11) DEFAULT NULL,
              `PAGE_ZONE_CTA_STATUS` int(11) DEFAULT NULL,
              `PAGE_ZONE_CTA_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              `PAGE_ZONE_CTA_ORDER` int(11) DEFAULT NULL,
              `PAGE_ZONE_CTA_LABEL` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
              `DESCRIPTION` longtext COLLATE utf8_swedish_ci,
              `TARGET` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              `STYLE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              `CTA_REF_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              `CTA_ID` int(11) DEFAULT NULL,
              `PAGE_ZONE_CTA_CTA_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              PRIMARY KEY (`PAGE_ZONE_CTA_CTA_ID`,`PAGE_ZONE_CTA_ID`,`PAGE_ZONE_CTA_TYPE`,`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
              KEY `IDX_6000D602B4EDB1E5622E2C229381310F15EAE15F55F4D23FD9DFAC6` (`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
              KEY `IDX_F83E37E1DF977A` (`CTA_ID`),
              KEY `IDX_F83E37B4EDB1E5622E2C2` (`PAGE_ID`,`LANGUE_ID`),
              KEY `IDX_F83E379AD6FB46C7151C14B4EDB1E5622E2C229381310BA07F8665F3424` (`PAGE_ZONE_CTA_ID`,`PAGE_ZONE_CTA_TYPE`,`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
              KEY `IDX_F83E37B4EDB1E5622E2C229381310BA07F8665F342437F55F4D23FD9DFA` (`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`PAGE_ZONE_MULTI_ORDER`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;');
       
                
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE IF EXISTS psa_page_multi_zone_multi_cta');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS `psa_page_multi_zone_multi_cta` (
            `PAGE_ZONE_CTA_ID` int(11) NOT NULL,
            `PAGE_ID` int(11) NOT NULL,
            `LANGUE_ID` int(11) NOT NULL,
            `PAGE_VERSION` int(11) NOT NULL,
            `AREA_ID` int(11) NOT NULL,
            `ZONE_ORDER` int(11) NOT NULL,
            `PAGE_ZONE_CTA_STATUS` int(11) DEFAULT NULL,
            `PAGE_ZONE_CTA_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
            `PAGE_ZONE_CTA_ORDER` int(11) DEFAULT NULL,
            `PAGE_ZONE_CTA_LABEL` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
            `DESCRIPTION` longtext COLLATE utf8_swedish_ci,
            `TARGET` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
            `STYLE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
            `CTA_REF_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
            `PAGE_ZONE_MULTI_ID` int(11) NOT NULL,
            `PAGE_ZONE_MULTI_TYPE` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
            `CTA_ID` int(11) DEFAULT NULL,
            PRIMARY KEY (`PAGE_ZONE_CTA_ID`,`PAGE_ZONE_CTA_TYPE`,`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`ZONE_ORDER`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
            KEY `IDX_605F4639B4EDB1E5622E2C229381310BA07F8665F342437F55F4D23FD9D` (`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`ZONE_ORDER`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
            KEY `IDX_605F4639E1DF977A` (`CTA_ID`),
            KEY `IDX_605F4639B4EDB1E5622E2C2` (`PAGE_ID`,`LANGUE_ID`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;');
        
        
        $this->addSql('CREATE TABLE IF NOT EXISTS `psa_page_multi_zone_multi_cta_cta` (
              `PAGE_ZONE_CTA_CTA_ID` int(11) NOT NULL,
              `PAGE_ZONE_CTA_ID` int(11) NOT NULL,
              `PAGE_ID` int(11) NOT NULL,
              `LANGUE_ID` int(11) NOT NULL,
              `PAGE_VERSION` int(11) NOT NULL,
              `AREA_ID` int(11) NOT NULL,
              `ZONE_ORDER` int(11) NOT NULL,
              `PAGE_ZONE_CTA_STATUS` int(11) DEFAULT NULL,
              `PAGE_ZONE_CTA_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              `PAGE_ZONE_CTA_ORDER` int(11) DEFAULT NULL,
              `PAGE_ZONE_CTA_LABEL` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
              `DESCRIPTION` longtext COLLATE utf8_swedish_ci,
              `TARGET` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              `STYLE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              `CTA_REF_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              `CTA_ID` int(11) DEFAULT NULL,
              `PAGE_ZONE_MULTI_ID` int(11) NOT NULL,
              `PAGE_ZONE_MULTI_TYPE` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
              `PAGE_ZONE_CTA_CTA_TYPE` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
              PRIMARY KEY (`PAGE_ZONE_CTA_CTA_ID`,`PAGE_ZONE_CTA_ID`,`PAGE_ZONE_CTA_TYPE`,`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`ZONE_ORDER`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
              KEY `IDX_F83E37E1DF977A` (`CTA_ID`),
              KEY `IDX_F83E37B4EDB1E5622E2C2` (`PAGE_ID`,`LANGUE_ID`),
              KEY `IDX_F83E37B4EDB1E5622E2C229381310BA07F8665F342437F55F4D23FD9DFA` (`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`ZONE_ORDER`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`),
              KEY `IDX_F83E379AD6FB46C7151C14B4EDB1E5622E2C229381310BA07F8665F3424` (`PAGE_ZONE_CTA_ID`,`PAGE_ZONE_CTA_TYPE`,`PAGE_ID`,`LANGUE_ID`,`PAGE_VERSION`,`AREA_ID`,`ZONE_ORDER`,`PAGE_ZONE_MULTI_ID`,`PAGE_ZONE_MULTI_TYPE`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;');
    }
}
