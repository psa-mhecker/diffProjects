<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150602142238 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Ndp_ModelGroupingSilhouette'
            WHERE `TEMPLATE_ID` = '98'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Ndp_ModeleRegroupementSilh'
            WHERE `TEMPLATE_ID` = '98'");
  
    }
}
