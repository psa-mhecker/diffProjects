<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150702171452 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_template` SET
            `TEMPLATE_ID` = '316',
            `TEMPLATE_TYPE_ID` = '1',
            `TEMPLATE_GROUP_ID` = '5',
            `TEMPLATE_LABEL` = 'Assistant de création de site',
            `TEMPLATE_PATH` = 'Ndp_Administration_AssistantSite',
            `TEMPLATE_PATH_FO` = NULL,
            `TEMPLATE_COMPLEMENT` = NULL,
            `PLUGIN_ID` = ''
            WHERE `TEMPLATE_ID` = '316';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_template` SET
            `TEMPLATE_ID` = '316',
            `TEMPLATE_TYPE_ID` = '1',
            `TEMPLATE_GROUP_ID` = '5',
            `TEMPLATE_LABEL` = 'Assistant de création de site',
            `TEMPLATE_PATH` = 'Citroen_Administration_AssistantSite',
            `TEMPLATE_PATH_FO` = NULL,
            `TEMPLATE_COMPLEMENT` = NULL,
            `PLUGIN_ID` = ''
            WHERE `TEMPLATE_ID` = '316';");
    }
}
