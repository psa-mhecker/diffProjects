<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150918110551 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Ndp_Administration_CopierColler' WHERE `TEMPLATE_ID` = '307';");
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Ndp_Administration_Diffusion' WHERE `TEMPLATE_ID` = '293';");
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Ndp_Administration_ImportExport'WHERE `TEMPLATE_ID` = '294';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Citroen_Administration_CopierColler' WHERE `TEMPLATE_ID` = '307';");
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Citroen_Administration_Diffusion' WHERE `TEMPLATE_ID` = '293';");
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Citroen_Administration_ImportExport'WHERE `TEMPLATE_ID` = '294';");
    }
}
