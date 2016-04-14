<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160201151652 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_sites_et_webservices_psa` set `SITE_RANGE_MANAGER` = 'https://rangemanager-rec.mpsa.com/app_inte.php/login'");
        $this->addSql("UPDATE `psa_liste_webservices`  set `ws_url`= 'https://mdendp00:rcpel8z6@rangemanager-rec.mpsa.com/app_inte.php/rm/api/v1/' where `ws_name`='WS_GEST_RANGE_MANAGER'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE `psa_sites_et_webservices_psa` set `SITE_RANGE_MANAGER` = 'https://rangemanager-integ.mpsa.com/app_inte.php/login'");
        $this->addSql("UPDATE `psa_liste_webservices`  set `ws_url`= 'https://mdendp00:rcpel8z6@rangemanager-integ.mpsa.com/app_inte.php/rm/api/v1/' where `ws_name`='WS_GEST_RANGE_MANAGER'");
    }
}
