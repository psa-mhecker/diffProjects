<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151005143619 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_finishing_site DROP FOREIGN KEY FK_CABBD6C684A4C519');
        $this->addSql('ALTER TABLE `psa_finishing_site` CHANGE COLUMN `COLOR_ID` `COLOR_ID` INT(11) NULL DEFAULT \'1\'');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C684A4C519 FOREIGN KEY (COLOR_ID) REFERENCES psa_finishing_color (ID)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_finishing_site DROP FOREIGN KEY FK_CABBD6C684A4C519');
        $this->addSql('ALTER TABLE `psa_finishing_site` CHANGE COLUMN `COLOR_ID` `COLOR_ID` INT(11) NULL');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C684A4C519 FOREIGN KEY (COLOR_ID) REFERENCES psa_finishing_color (ID) ON DELETE SET NULL');

    }
}
