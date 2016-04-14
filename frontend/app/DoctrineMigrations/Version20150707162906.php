<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150707162906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE psa_model_view_angle (CODE VARCHAR(3) NOT NULL, START_ANGLE TINYINT(1) NOT NULL, `ANGLE_ORDER` INT NOT NULL, LCDV4 VARCHAR(4) NOT NULL, PRIMARY KEY(LCDV4)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_model_view_angle ADD CONSTRAINT FK_78A5F41F68469EFD FOREIGN KEY (LCDV4) REFERENCES psa_model (LCDV4) ON DELETE CASCADE');
          }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE psa_model_view_angle');
    }
}
