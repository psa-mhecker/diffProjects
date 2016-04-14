<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150122162839 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_page_zone_cta (ID INT AUTO_INCREMENT NOT NULL, PAGE_ID INT DEFAULT NULL, PAGE_VERSION INT DEFAULT NULL, ZONE_TEMPLATE_ID INT DEFAULT NULL, LANGUE_ID INT DEFAULT NULL, CTA_ID INT NOT NULL, INDEX IDX_B03DE77CB4EDB1E29381310F15EAE155622E2C2 (PAGE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID, LANGUE_ID), INDEX IDX_B03DE77CE1DF977A (CTA_ID), INDEX IDX_B03DE77CB4EDB1E5622E2C2 (PAGE_ID, LANGUE_ID), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_cta (ID INT AUTO_INCREMENT NOT NULL, TYPE VARCHAR(255) NOT NULL, USED_COUNT INT NOT NULL, TITLE_BO VARCHAR(255) NOT NULL, TITLE VARCHAR(255) NOT NULL, ACTION LONGTEXT NOT NULL, TARGET VARCHAR(50) NOT NULL, MEDIA_WEB_ID INT DEFAULT NULL, MEDIA_MOBILE_ID INT DEFAULT NULL, INDEX IDX_F0F9A97620A3EBFF (MEDIA_WEB_ID), INDEX IDX_F0F9A976899BF056 (MEDIA_MOBILE_ID), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CB4EDB1E29381310F15EAE155622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID) REFERENCES psa_page_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID)');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CB4EDB1E5622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID) REFERENCES psa_page (PAGE_ID, LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_cta ADD CONSTRAINT FK_F0F9A97620A3EBFF FOREIGN KEY (MEDIA_WEB_ID) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('ALTER TABLE psa_cta ADD CONSTRAINT FK_F0F9A976899BF056 FOREIGN KEY (MEDIA_MOBILE_ID) REFERENCES psa_media (MEDIA_ID)');

        // Test data
        $this->addSql("INSERT INTO `psa_cta` (`ID`, `TYPE`, `USED_COUNT`, `TITLE_BO`, `TITLE`, `ACTION`, `TARGET`, `MEDIA_WEB_ID`, `MEDIA_MOBILE_ID`) VALUES (NULL, 'standard', '1', 'CTA 1', 'CTA 1', 'http://www.google.com', '_blank', NULL, NULL), (NULL, 'standard', '1', 'CTA 2', 'CTA 2', '#', '_self', NULL, NULL)");
        $this->addSql("INSERT INTO `psa_page_zone_cta` (`ID`, `PAGE_ID`, `PAGE_VERSION`, `ZONE_TEMPLATE_ID`, `LANGUE_ID`, `CTA_ID`) VALUES (NULL, '1', '10', '1976', '2', '1'), (NULL, '1', '10', '1976', '2', '2')");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_page_zone_cta DROP FOREIGN KEY FK_B03DE77CE1DF977A');
        $this->addSql('DROP TABLE psa_page_zone_cta');
        $this->addSql('DROP TABLE psa_cta');
    }
}
