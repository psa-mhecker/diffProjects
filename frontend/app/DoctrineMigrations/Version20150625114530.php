<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150625114530 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("update psa_liste_webservices set response_type='array',response_format='json' WHERE ws_name='WS_ANNUPDV'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("update psa_liste_webservices set response_type=NULL,response_format=NULL WHERE ws_name='WS_ANNUPDV'");
     }
}
