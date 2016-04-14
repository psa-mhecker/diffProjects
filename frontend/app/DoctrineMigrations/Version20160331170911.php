<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160331170911 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX model_code ON psa_ws_gdg_model_silhouette_site');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site DROP FOREIGN KEY FK_9FAC456B5622E2C2');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site DROP FOREIGN KEY FK_9FAC456BF1B5AEBC');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site CHANGE COLOR_ID COLOR_ID INT DEFAULT NULL');
        $this->addSql('UPDATE psa_ws_gdg_model_silhouette_site SET COLOR_ID=1');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site ADD CONSTRAINT FK_50F5A57D84A4C519 FOREIGN KEY (COLOR_ID) REFERENCES psa_finishing_color (ID) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_50F5A57D84A4C519 ON psa_ws_gdg_model_silhouette_site (COLOR_ID)');
        $this->addSql('DROP INDEX idx_9fac456b5622e2c2 ON psa_ws_gdg_model_silhouette_site');
        $this->addSql('CREATE INDEX IDX_50F5A57D5622E2C2 ON psa_ws_gdg_model_silhouette_site (LANGUE_ID)');
        $this->addSql('DROP INDEX idx_9fac456bf1b5aebc ON psa_ws_gdg_model_silhouette_site');
        $this->addSql('CREATE INDEX IDX_50F5A57DF1B5AEBC ON psa_ws_gdg_model_silhouette_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site ADD CONSTRAINT FK_9FAC456B5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site ADD CONSTRAINT FK_9FAC456BF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site DROP FOREIGN KEY FK_50F5A57D84A4C519');
        $this->addSql('DROP INDEX IDX_50F5A57D84A4C519 ON psa_ws_gdg_model_silhouette_site');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site DROP FOREIGN KEY FK_9FAC456B5622E2C2');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site DROP FOREIGN KEY FK_9FAC456BF1B5AEBC');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site CHANGE COLOR_ID COLOR_ID INT DEFAULT 1');
        $this->addSql('CREATE UNIQUE INDEX model_code ON psa_ws_gdg_model_silhouette_site (LCDV6, SITE_ID, LANGUE_ID, GROUPING_CODE)');
        $this->addSql('DROP INDEX idx_50f5a57d5622e2c2 ON psa_ws_gdg_model_silhouette_site');
        $this->addSql('CREATE INDEX IDX_9FAC456B5622E2C2 ON psa_ws_gdg_model_silhouette_site (LANGUE_ID)');
        $this->addSql('DROP INDEX idx_50f5a57df1b5aebc ON psa_ws_gdg_model_silhouette_site');
        $this->addSql('CREATE INDEX IDX_9FAC456BF1B5AEBC ON psa_ws_gdg_model_silhouette_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site ADD CONSTRAINT FK_9FAC456B5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site ADD CONSTRAINT FK_9FAC456BF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
    }
}
