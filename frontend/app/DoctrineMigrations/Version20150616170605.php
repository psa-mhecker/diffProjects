<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * setting response_type & response_format for the range manager webservice
 */
class Version20150616170605 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql(
            "UPDATE  `psa-ndp`.`psa_liste_webservices` SET  `response_type` =  'array', `response_format` =  'json' WHERE  `psa_liste_webservices`.`ws_id` =5;"
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql(
            "UPDATE  `psa-ndp`.`psa_liste_webservices` SET  `response_type` = NULL , `response_format` = NULL WHERE  `psa_liste_webservices`.`ws_id` =5;"
        );

    }

    public function preUp(Schema $schema)
    {
        $this->addSql(
            "ALTER TABLE  psa_liste_webservices ADD  response_type VARCHAR( 255 ) NULL DEFAULT NULL , ADD  response_format VARCHAR( 10 ) NULL DEFAULT NULL"
        );
    }

    public function postDown(Schema $schema)
    {
        $this->addSql(
            "ALTER TABLE  psa_liste_webservices DROP  response_type ,DROP  response_format ;"
        );
    }

}
