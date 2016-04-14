<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150619154659 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_zone SET ZONE_BO_PATH="Cms_Page_Ndp_Pf44DevenirAgent" WHERE ZONE_ID=763');
        $this->addSql('UPDATE psa_zone SET ZONE_LABEL="NDP_PF44_DEVENIR_AGENT" WHERE ZONE_ID=763');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_zone SET ZONE_BO_PATH="Cms_Page_Ndp_Pf44DevenirAgentStrategy" WHERE ZONE_ID=763');
        $this->addSql('UPDATE psa_zone SET ZONE_LABEL="NDP_Pf44DevenirAgentStrategy" WHERE ZONE_ID=763');
    }
}
