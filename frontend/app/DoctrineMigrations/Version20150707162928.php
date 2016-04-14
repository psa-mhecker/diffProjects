<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150707162928 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE psa_ws_gdg_model_silhouette RENAME TO psa_ws_gdg_model_silhouette_site");
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Ndp_ModelGroupingSilhouetteSite' WHERE `TEMPLATE_ID` = '98'");
        $this->addSql('CREATE TABLE psa_ws_gdg_model_silhouette (ID INT AUTO_INCREMENT NOT NULL, GENDER VARCHAR(2) NOT NULL, LCDV6 VARCHAR(6) NOT NULL UNIQUE, MODEL VARCHAR(255) NOT NULL, SILHOUETTE VARCHAR(255) NOT NULL, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_gdg_model_silhouette_angle (ID INT AUTO_INCREMENT NOT NULL, CODE VARCHAR(3) NULL, ANGLE TINYINT(1) NOT NULL, `ANGLE_ORDER` INT NOT NULL, MODEL_SILHOUETTE_ID INT NOT NULL, CONSTRAINT FK_896BD845EBE647F0 FOREIGN KEY (MODEL_SILHOUETTE_ID) REFERENCES psa_ws_gdg_model_silhouette(ID) ON DELETE CASCADE , PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_angle ADD CONSTRAINT FK_896BD845EBE647F0 FOREIGN KEY (MODEL_SILHOUETTE_ID) REFERENCES psa_ws_gdg_model_silhouette (ID)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_angle DROP FOREIGN KEY FK_896BD845EBE647F0');
        $this->addSql('DROP TABLE psa_ws_gdg_model_silhouette_angle');
        $this->addSql('DROP TABLE psa_ws_gdg_model_silhouette');
        $this->addSql("ALTER TABLE psa_ws_gdg_model_silhouette_site RENAME TO psa_ws_gdg_model_silhouette");
        $this->addSql("UPDATE `psa_template` SET `TEMPLATE_PATH` = 'Ndp_ModelGroupingSilhouette' WHERE `TEMPLATE_ID` = '98'");

    }
}
