<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401160757 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE psa_pdv_deveniragent (
            SITE_ID INT NOT NULL,
            PDV_DEVENIRAGENT_ID INT NOT NULL,
            PDV_DEVENIRAGENT_NAME VARCHAR(255) DEFAULT NULL,
            PDV_DEVENIRAGENT_DESC VARCHAR(255) DEFAULT NULL,
            PDV_DEVENIRAGENT_ADDRESS1 VARCHAR(255) DEFAULT NULL,
            PDV_DEVENIRAGENT_ADDRESS2 VARCHAR(255) DEFAULT NULL,
            PDV_DEVENIRAGENT_ZIPCODE VARCHAR(10) DEFAULT NULL,
            PDV_DEVENIRAGENT_CITY VARCHAR(255) DEFAULT NULL,
            PDV_DEVENIRAGENT_COUNTRY VARCHAR(2) DEFAULT NULL,
            PDV_DEVENIRAGENT_EMAIL VARCHAR(255) DEFAULT NULL,
            PDV_DEVENIRAGENT_TEL1 VARCHAR(20) DEFAULT NULL,
            PDV_DEVENIRAGENT_TEL2 VARCHAR(20) DEFAULT NULL,
            PDV_DEVENIRAGENT_FAX VARCHAR(20) DEFAULT NULL,
            PDV_DEVENIRAGENT_RRDI VARCHAR(10) DEFAULT NULL,
            PDV_DEVENIRAGENT_LAT DOUBLE PRECISION NOT NULL,
            PDV_DEVENIRAGENT_LNG DOUBLE PRECISION NOT NULL,
            PDV_DEVENIRAGENT_LIAISON_ID INT DEFAULT NULL,
            INDEX PDV_DEVENIRAGENT_ID (PDV_DEVENIRAGENT_ID),
            PRIMARY KEY(SITE_ID, PDV_DEVENIRAGENT_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
            ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE psa_pdv_deveniragent');
    }
}
