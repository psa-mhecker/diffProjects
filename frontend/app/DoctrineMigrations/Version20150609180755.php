<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * This query was added for tests sake
 * It should be removed once the webservices up and running without mocks
 */
class Version20150609180755 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE  psa_liste_webservices SET auth_login ='tomcat',auth_password ='tomcat'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE psa_liste_webservices  SET auth_login =NULL,auth_password =NULL");

    }
}
