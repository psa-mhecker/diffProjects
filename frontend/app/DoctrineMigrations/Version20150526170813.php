<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150526170813 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_ws_gdg_model_silhouette` (`ID`, `GENDER`, `LCDV6`, `GROUPING_CODE`, `COMMERCIAL_LABEL`, `SHOW_FINISHING`, `NEW_COMMERCIAL_STRIP`, `SPECIAL_OFFER_COMMERCIAL_STRIP`, `SPECIAL_SERIES_COMMERCIAL_STRIP`, `SHOW_IN_CONFIG`, `STOCK_WEBSTORE`, `LANGUE_ID`, `SITE_ID`) VALUES
(1,	'VP',	'1PW2CA',	'S0000154',	'508 RXH',	0,	0,	0,	0,	0,	0,	1,	2),
(2,	'VP',	'1PW2A4',	'S0000034',	'nouvelle 508 berline',	0,	0,	0,	0,	0,	0,	1,	2);
");

        $this->addSql("INSERT INTO `psa_ws_gdg_model_silhouette_upselling` (`ID`, `FINISHING_CODE`, `FINISHING_LABEL`, `UPSELLING`, `MODEL_SILHOUETTE_ID`, `FINISHING_REFERENCE`) VALUES
(1,	'00000174',	'Access',	1,	2,	1),
(3,	'00000159',	'Active',	1,	2,	1),
(4,	'00000044',	'Style',	1,	2,	1),
(5,	'00000176',	'Allure',	1,	2,	1),
(6,	'00000199',	'RXH',	1,	1,	6),
(8,	'00000235',	'508 RXH HYbrid4',	1,	1,	6);");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('truncate psa_ws_gdg_model_silhouette_upselling');
        $this->addSql('truncate psa_ws_gdg_model_silhouette');
    }
}
