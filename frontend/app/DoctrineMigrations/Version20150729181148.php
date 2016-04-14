<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150729181148 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_liste_webservices` SET
            `ws_id` = '15',
            `ws_name` = 'WS_BO_FORMS',
            `ws_url` = 'https://tools-ndp-dev.peugeot.com/relay/relay.php?url=http://sgp.wssoap.preprod.inetpsa.com/dcr/integ/ap/services/BOFormService'
            WHERE `ws_name` = 'WS_BO_FORMS';");
        
        $this->addSql("INSERT INTO `psa_site_webservice` (`site_id`, `ws_id`, `status`, `service_key`, `response_type`, `response_format`, `cache_ttl`)
VALUES ('2', '15', '1', '', '', '', NULL);");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `psa_site_webservice` WHERE `ws_id` = '15';");
    }
}
