<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150902144323 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("TRUNCATE TABLE psa_segmentation_finition_site");
        $this->addSql("ALTER TABLE psa_segmentation_finition_site DROP INDEX UNIQ_5906659B4180DD2C");
        $this->addSql("ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT segment_code UNIQUE (CODE, LANGUE_ID, SITE_ID)");
        $this->addSql('ALTER TABLE psa_model_site DROP FOREIGN KEY FK_C3C59E3868469EFD');
        $this->addSql('ALTER TABLE psa_model_site ADD CONSTRAINT FK_C3C59E3868469EFD FOREIGN KEY (LCDV4) REFERENCES psa_model (LCDV4) ON DELETE CASCADE');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("TRUNCATE TABLE psa_segmentation_finition_site");
        $this->addSql("ALTER TABLE psa_segmentation_finition_site DROP INDEX segment_code");
        $this->addSql("ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT UNIQ_5906659B4180DD2C UNIQUE (CODE)");
        $this->addSql('ALTER TABLE psa_model_site DROP FOREIGN KEY FK_C3C59E3868469EFD');
        $this->addSql('ALTER TABLE psa_model_site ADD CONSTRAINT FK_C3C59E3868469EFD FOREIGN KEY (LCDV4) REFERENCES psa_model (LCDV4)');
    }
}
