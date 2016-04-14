<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160311140656 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `psa_ws_gdg_model_silhouette_site` DROP INDEX `model_code`');
        $this->addSql('ALTER TABLE `psa_ws_gdg_model_silhouette_site` ADD UNIQUE  `model_code` (  `LCDV6` ,  `SITE_ID` ,  `LANGUE_ID` ,  `GROUPING_CODE` )');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
