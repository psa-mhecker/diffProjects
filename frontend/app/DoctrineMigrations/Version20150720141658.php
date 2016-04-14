<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150720141658 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE psa_finishing_site (ID INT AUTO_INCREMENT NOT NULL UNIQUE, CODE VARCHAR(8) NOT NULL, FINITION VARCHAR(255) NOT NULL, VERSIONS_CRITERION VARCHAR(255) DEFAULT NULL, CUSTOMER_TYPE VARCHAR(255) DEFAULT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, COLOR_ID INT DEFAULT NULL, BADGE_ID INT DEFAULT NULL, INDEX IDX_CABBD6C6F1B5AEBC (SITE_ID), INDEX IDX_CABBD6C65622E2C2 (LANGUE_ID), INDEX IDX_CABBD6C684A4C519 (COLOR_ID), INDEX IDX_CABBD6C69DC1850 (BADGE_ID), PRIMARY KEY(CODE, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C6F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C65622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C684A4C519 FOREIGN KEY (COLOR_ID) REFERENCES psa_finishing_color (ID)');
        $this->addSql('ALTER TABLE psa_finishing_site ADD CONSTRAINT FK_CABBD6C69DC1850 FOREIGN KEY (BADGE_ID) REFERENCES psa_finishing_badge (ID)');

         $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_SEGMENTATION_OF_FINITION', NULL, 2, NULL, NULL, 1, NULL)
            ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_SEGMENTATION_OF_FINITION', 1, 1, 'Segmentation de la finition')
            ");
}

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE psa_finishing_site');
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_SEGMENTATION_OF_FINITION'
                 )
                "
            );
        }
        
    }
}
