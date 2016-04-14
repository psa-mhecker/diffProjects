<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150710102929 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE psa_ws_gdg_model_silhouette_site ADD UNIQUE (LCDV6)");
        $this->addSql("DROP TABLE psa_ws_gdg_model_silhouette_upselling");
        $this->addSql('CREATE TABLE psa_ws_gdg_model_silhouette_upselling (ID INT AUTO_INCREMENT NOT NULL, FINISHING_CODE VARCHAR(8) NOT NULL, FINISHING_LABEL VARCHAR(255) NOT NULL, UPSELLING TINYINT(1) DEFAULT \'1\', MODEL_SILHOUETTE_ID INT DEFAULT NULL, FINISHING_REFERENCE INT DEFAULT NULL, INDEX IDX_23B42BB5EBE647F0 (MODEL_SILHOUETTE_ID), INDEX IDX_23B42BB5127F2945 (FINISHING_REFERENCE), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5EBE647F0 FOREIGN KEY (MODEL_SILHOUETTE_ID) REFERENCES psa_ws_gdg_model_silhouette_site (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5127F2945 FOREIGN KEY (FINISHING_REFERENCE) REFERENCES psa_ws_gdg_model_silhouette_upselling (ID) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE psa_ws_gdg_model_silhouette_site DROP INDEX LCDV6");
        $this->addSql("DROP TABLE psa_ws_gdg_model_silhouette_upselling");
        $this->addSql('CREATE TABLE psa_ws_gdg_model_silhouette_upselling (ID INT AUTO_INCREMENT NOT NULL, FINISHING_CODE VARCHAR(8) NOT NULL, FINISHING_LABEL VARCHAR(255) NOT NULL, UPSELLING TINYINT(1) DEFAULT \'1\', MODEL_SILHOUETTE_ID INT DEFAULT NULL, FINISHING_REFERENCE INT DEFAULT NULL, INDEX IDX_23B42BB5EBE647F0 (MODEL_SILHOUETTE_ID), INDEX IDX_23B42BB5127F2945 (FINISHING_REFERENCE), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5EBE647F0 FOREIGN KEY (MODEL_SILHOUETTE_ID) REFERENCES psa_ws_gdg_model_silhouette_site (ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5127F2945 FOREIGN KEY (FINISHING_REFERENCE) REFERENCES psa_ws_gdg_model_silhouette_upselling (ID)');
    }
}
