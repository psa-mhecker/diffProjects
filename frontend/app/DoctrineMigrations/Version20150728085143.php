<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150728085143 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE psa_liste_webservices set ws_url='https://tools-ndp-dev.peugeot.com/relay/relay.php?url=http://ws-store.peugeot.preprod.inetpsa.com/services/WebstoreServices.asmx?login=mdendp00&password=rcpel8z6' where ws_id=8");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE psa_liste_webservices set ws_url='http://ws-store.peugeot.inet.psa.com' where ws_id=8");
    }
}
