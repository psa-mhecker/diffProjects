<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150527142510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            "CREATE TABLE psa_pdv_service (
                    PDV_SERVICE_ID int(11) NOT NULL AUTO_INCREMENT,
                    SITE_ID int(11) NOT NULL,
                    LANGUE_ID int(11) NOT NULL,
                    PDV_SERVICE_CODE varchar(255) COLLATE utf8_swedish_ci NOT NULL,
                    PDV_SERVICE_LABEL varchar(255) COLLATE utf8_swedish_ci NOT NULL,
                    PDV_SERVICE_LABEL_PERSO varchar(255) COLLATE utf8_swedish_ci NOT NULL,
                    PDV_SERVICE_TYPE varchar(255) COLLATE utf8_swedish_ci NOT NULL,
                    PDV_SERVICE_ORDER int(11) DEFAULT NULL,
                    MEDIA_ID int(11) DEFAULT NULL,
                    PDV_SERVICE_ACTIF int(11) NOT NULL DEFAULT '0',
                    PRIMARY KEY (SITE_ID,LANGUE_ID,PDV_SERVICE_CODE),
                    UNIQUE KEY PDV_SERVICE_ID (PDV_SERVICE_ID),
                    KEY PDV_SERVICE_LABEL (PDV_SERVICE_LABEL)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1
                "
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE psa_pdv_service');

    }
}
