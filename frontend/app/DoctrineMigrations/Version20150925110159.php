<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150925110159 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
       $this->addSql('ALTER TABLE psa_finishing_site DROP FOREIGN KEY FK_CABBD6C684A4C519');
        $this->addSql('ALTER TABLE psa_finishing_site DROP FOREIGN KEY FK_CABBD6C69DC1850');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C684A4C519 FOREIGN KEY (COLOR_ID) REFERENCES psa_finishing_color (ID) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C69DC1850 FOREIGN KEY (BADGE_ID) REFERENCES psa_finishing_badge (ID) ON DELETE SET NULL');
        
        $this->addSql('ALTER TABLE psa_vehicle_category_site DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_vehicle_category_site DROP FOREIGN KEY FK_92D64B4611D3633A');
        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD CONSTRAINT FK_92D64B4611D3633A FOREIGN KEY (ID) REFERENCES psa_vehicle_category (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD PRIMARY KEY (ID, LANGUE_ID, SITE_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_finishing_site DROP FOREIGN KEY FK_CABBD6C684A4C519');
        $this->addSql('ALTER TABLE psa_finishing_site DROP FOREIGN KEY FK_CABBD6C69DC1850');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C684A4C519 FOREIGN KEY (COLOR_ID) REFERENCES psa_finishing_color (ID)');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C69DC1850 FOREIGN KEY (BADGE_ID) REFERENCES psa_finishing_badge (ID)');
        
        $this->addSql('ALTER TABLE psa_vehicle_category_site DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_vehicle_category_site DROP FOREIGN KEY FK_92D64B4611D3633A');
        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD CONSTRAINT FK_92D64B4611D3633A FOREIGN KEY (ID) REFERENCES psa_vehicle_category (ID)');
        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD PRIMARY KEY (ID, LANGUE_ID, SITE_ID)');
    }
}
