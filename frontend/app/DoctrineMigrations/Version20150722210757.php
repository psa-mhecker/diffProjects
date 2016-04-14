<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150722210757 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE psa_liste_webservices set ws_url='https://tools-ndp-dev.peugeot.com/relay/relay.php?url=http://sgp.wssoap.inetpsa.com/cfg/services/Select&login=mdendp00&password=rcpel8z6' where ws_id=9");
        $this->addSql("UPDATE psa_liste_webservices set ws_url='https://tools-ndp-dev.peugeot.com/relay/relay.php?url=http://sgp.wssoap.inetpsa.com/cfg/services/Config&login=mdendp00&password=rcpel8z6' where ws_id=10");
        $this->addSql("UPDATE psa_liste_webservices set ws_url='https://tools-ndp-dev.peugeot.com/relay/relay.php?url=http://sgp.wssoap.inetpsa.com/cfg/services/CompareGrade&login=mdendp00&password=rcpel8z6' where ws_id=12");
        
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
