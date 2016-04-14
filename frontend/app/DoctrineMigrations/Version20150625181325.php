<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150625181325 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE psa_zone SET ZONE_FO_PATH="Pf11RecherchePointDeVenteStrategy" WHERE ZONE_ID=812');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE psa_zone SET ZONE_FO_PATH=NULL WHERE ZONE_ID=812');
    }
}
