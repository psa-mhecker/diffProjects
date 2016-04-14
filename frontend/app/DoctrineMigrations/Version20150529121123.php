<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150529121123 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_zone` SET `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pf6DragDrop' WHERE `ZONE_ID` = '786';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_zone` SET `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pf6DragAndDrop' WHERE `ZONE_ID` = '786';");

    }
}
