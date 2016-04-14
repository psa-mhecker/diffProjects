<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150729161859 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE psa_ws_gdg_model_silhouette_upselling');
        $this->addSql("UPDATE psa_segmentation_finition SET CODE='VP' WHERE ID=1");

        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP FOREIGN KEY FK_5906659B11D3633A');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP INDEX IDX_5906659B11D3633A');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD ID_CENTRAL INT DEFAULT NULL, CHANGE ID ID INT NOT NULL');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD CODE VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5906659B4180DD2C ON psa_segmentation_finition_site (CODE)');

        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT FK_5906659BFEB7F4BA FOREIGN KEY (ID_CENTRAL) REFERENCES psa_segmentation_finition (ID)');
        $this->addSql('CREATE INDEX IDX_5906659BFEB7F4BA ON psa_segmentation_finition_site (ID_CENTRAL)');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD PRIMARY KEY (ID)');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site CHANGE ID ID INT AUTO_INCREMENT NOT NULL, CHANGE LANGUE_ID LANGUE_ID INT DEFAULT NULL, CHANGE SITE_ID SITE_ID INT DEFAULT NULL');
        
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling DROP FOREIGN KEY FK_23B42BB5127F2945');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling CHANGE FINISHING_REFERENCE FINISHING_REFERENCE VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD VEHICULE_USE VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD LANGUE_ID INT DEFAULT NULL, ADD SITE_ID INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling CHANGE UPSELLING UPSELLING TINYINT( 1 ) NULL DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE psa_segmentation_finition_site');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP FOREIGN KEY FK_5906659BFEB7F4BA');
        $this->addSql('DROP INDEX IDX_5906659BFEB7F4BA ON psa_segmentation_finition_site');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP ID_CENTRAL, CHANGE ID ID INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT FK_5906659B11D3633A FOREIGN KEY (ID) REFERENCES psa_segmentation_finition (ID) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5906659B11D3633A ON psa_segmentation_finition_site (ID)');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD PRIMARY KEY (ID, LANGUE_ID, SITE_ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling CHANGE FINISHING_REFERENCE FINISHING_REFERENCE INT DEFAULT NULL');
        $this->addSql('TRUNCATE TABLE psa_ws_gdg_model_silhouette_upselling');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5127F2945 FOREIGN KEY (FINISHING_REFERENCE) REFERENCES psa_ws_gdg_model_silhouette_upselling (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling DROP VEHICULE_USE');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling DROP LANGUE_ID, DROP SITE_ID');
        $this->addSql('DROP INDEX UNIQ_5906659B4180DD2C ON psa_segmentation_finition_site');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP CODE');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site CHANGE ID ID INT NOT NULL, CHANGE LANGUE_ID LANGUE_ID INT NOT NULL, CHANGE SITE_ID SITE_ID INT NOT NULL');

    }
}
