<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160222160402 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_sites_et_webservices_psa` set `SITE_RANGE_MANAGER` = 'http://rangemanager.rec.inetpsa.com/app_inte.php/'");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_sites_et_webservices_psa` set `SITE_RANGE_MANAGER` = 'https://rangemanager-rec.mpsa.com/app_inte.php/login'");

    }
}
