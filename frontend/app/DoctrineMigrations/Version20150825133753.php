<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150825133753 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE psa_ws_gdg_model_silhouette_angle');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_angle DROP ID, CHANGE CODE CODE VARCHAR(3) NOT NULL, CHANGE ANGLE ANGLE TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_angle ADD PRIMARY KEY (MODEL_SILHOUETTE_ID, CODE)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE psa_ws_gdg_model_silhouette_angle');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_angle DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_angle ADD ID INT AUTO_INCREMENT NOT NULL PRIMARY KEY, CHANGE CODE CODE VARCHAR(3) DEFAULT NULL COLLATE utf8_swedish_ci, CHANGE ANGLE ANGLE TINYINT(1) NOT NULL');
    }
}
