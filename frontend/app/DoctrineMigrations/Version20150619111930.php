<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150619111930 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // change zone label typo
        $this->addSql("UPDATE `psa_zone_template` SET ZONE_TEMPLATE_LABEL = 'NDP_PT22_MY_PEUGEOT' WHERE ZONE_TEMPLATE_LABEL = 'NPD_PT22_MY_PEUGEOT'");
        $this->addSql("UPDATE `psa_zone_template` SET ZONE_TEMPLATE_LABEL = 'NDP_PT3_JE_VEUX' WHERE ZONE_TEMPLATE_LABEL = 'NPD_PT3_JE_VEUX'");

        // changement nom de la tranche
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'PF30 Post code pop in' WHERE LABEL_ID = 'NDP_PF30_POPIN_CODE_POSTAL'");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // suppression des tranches du gabarit

    }
}
