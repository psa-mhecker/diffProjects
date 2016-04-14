<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160218094806 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE  psa_liste_webservices SET auth_login ='mdendp00',auth_password ='rcpel8z6' WHERE ws_name = 'WS_GEST_RANGE_MANAGER'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE  psa_liste_webservices SET auth_login ='tomcat',auth_password ='tomcat' WHERE ws_name = 'WS_GEST_RANGE_MANAGER'");
    }
}
