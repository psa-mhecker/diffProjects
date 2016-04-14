<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150513114138 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE psa_modele_config DROP FOREIGN KEY FK_87FC717CF1B5AEBC');
        $this->addSql('DROP INDEX IDX_87FC717CF1B5AEBC ON psa_modele_config');
        $this->addSql('ALTER TABLE psa_modele_config DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_modele_config ADD ID INT NOT NULL, CHANGE MONTEE_GAMME MONTEE_GAMME TINYINT(1) DEFAULT NULL, CHANGE SHOW_CARAC SHOW_CARAC TINYINT(1) DEFAULT NULL, CHANGE SHOW_COMPARISONCHART SHOW_COMPARISONCHART TINYINT(1) DEFAULT NULL, CHANGE SHOW_COMPARISONCHART_BUTTON_OPEN SHOW_COMPARISONCHART_BUTTON_OPEN TINYINT(1) DEFAULT NULL, CHANGE SHOW_COMPARISONCHART_BUTTON_CLOSE SHOW_COMPARISONCHART_BUTTON_CLOSE TINYINT(1) DEFAULT NULL, CHANGE SHOW_COMPARISONCHART_BUTTON_DIFF SHOW_COMPARISONCHART_BUTTON_DIFF TINYINT(1) DEFAULT NULL, CHANGE SHOW_COMPARISONCHART_BUTTON_PRINT SHOW_COMPARISONCHART_BUTTON_PRINT TINYINT(1) DEFAULT NULL, CHANGE CTA_CONFIGURE_DISPLAY CTA_CONFIGURE_DISPLAY TINYINT(1) DEFAULT NULL, CHANGE CTA_STOCK_DISPLAY CTA_STOCK_DISPLAY TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_modele_config ADD CONSTRAINT FK_87FC717C11D3633A FOREIGN KEY (ID) REFERENCES psa_type_couleur (ID)');
        $this->addSql('CREATE INDEX IDX_87FC717C11D3633A ON psa_modele_config (ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

           $this->addSql('ALTER TABLE psa_modele_config DROP FOREIGN KEY FK_87FC717C11D3633A');
        $this->addSql('DROP INDEX IDX_87FC717C11D3633A ON psa_modele_config');
        $this->addSql('ALTER TABLE psa_modele_config DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_modele_config DROP ID, CHANGE MONTEE_GAMME MONTEE_GAMME INT DEFAULT NULL, CHANGE SHOW_CARAC SHOW_CARAC INT DEFAULT NULL, CHANGE SHOW_COMPARISONCHART SHOW_COMPARISONCHART INT DEFAULT NULL, CHANGE SHOW_COMPARISONCHART_BUTTON_OPEN SHOW_COMPARISONCHART_BUTTON_OPEN INT DEFAULT NULL, CHANGE SHOW_COMPARISONCHART_BUTTON_CLOSE SHOW_COMPARISONCHART_BUTTON_CLOSE INT DEFAULT NULL, CHANGE SHOW_COMPARISONCHART_BUTTON_DIFF SHOW_COMPARISONCHART_BUTTON_DIFF INT DEFAULT NULL, CHANGE SHOW_COMPARISONCHART_BUTTON_PRINT SHOW_COMPARISONCHART_BUTTON_PRINT INT DEFAULT NULL, CHANGE CTA_CONFIGURE_DISPLAY CTA_CONFIGURE_DISPLAY INT DEFAULT NULL, CHANGE CTA_STOCK_DISPLAY CTA_STOCK_DISPLAY INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_modele_config ADD CONSTRAINT FK_87FC717CF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('CREATE INDEX IDX_87FC717CF1B5AEBC ON psa_modele_config (SITE_ID)');
        $this->addSql('ALTER TABLE psa_modele_config ADD PRIMARY KEY (SITE_ID, LANGUE_ID)');
    }
}
