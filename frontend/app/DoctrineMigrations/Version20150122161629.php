<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150122161629 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_content_category_category (CONTENT_CATEGORY_ORDER INT NOT NULL, PARENT_ID INT NOT NULL, CHILD_ID INT NOT NULL, INDEX IDX_F7DAE8E3EF5927F (PARENT_ID), INDEX IDX_F7DAE8E3231C18B7 (CHILD_ID), PRIMARY KEY(PARENT_ID, CHILD_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_content_category_category ADD CONSTRAINT FK_F7DAE8E3EF5927F FOREIGN KEY (PARENT_ID) REFERENCES psa_content_category (CONTENT_CATEGORY_ID)');
        $this->addSql('ALTER TABLE psa_content_category_category ADD CONSTRAINT FK_F7DAE8E3231C18B7 FOREIGN KEY (CHILD_ID) REFERENCES psa_content_category (CONTENT_CATEGORY_ID)');

        $this->addSql('ALTER TABLE psa_content_category ADD CONTENT_CATEGORY_ORDER INT NOT NULL, ADD LANGUE_ID INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_content_category ADD CONSTRAINT FK_27C3EEBC5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('CREATE INDEX IDX_27C3EEBC5622E2C2 ON psa_content_category (LANGUE_ID)');

        // Test data
        $this->addSql("INSERT INTO `psa_content_category` (`CONTENT_CATEGORY_ID`, `SITE_ID`, `CONTENT_CATEGORY_PARENT_ID`, `CONTENT_CATEGORY_LABEL`, `CONTENT_TYPE_ID`, `CONTENT_CATEGORY_RESEARCH`, `CONTENT_CATEGORY_ORDER`, `LANGUE_ID`) VALUES (3, 5, NULL, 'Informations Générales', 11, '-Sans nom-', 1, 1), (4, 5, NULL, 'Services Connectés', 11, '-Sans nom-', 6, 1), (9, 5, NULL, 'Coyotte Séries', 11, '-Sans nom-', 1, 1), (10, 5, NULL, 'Not a FAQ', 1, '-Sans nom-', 1, 1), (11, 5, NULL, 'Services', 11, '-Sans nom-', 4, 1)");
        $this->addSql("UPDATE `psa_content_version` SET `CONTENT_CATEGORY_ID` = '9' WHERE `CONTENT_ID` =1736 OR  `CONTENT_ID`=1737 OR `CONTENT_ID`=1738");
        $this->addSql("INSERT INTO `psa_content_category_category` (`PARENT_ID`, `CHILD_ID`, `CONTENT_CATEGORY_ORDER`) VALUES ('4', '9', '1')");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // Remove test data alter
        $this->addSql("UPDATE `psa_content_version` SET `CONTENT_CATEGORY_ID` = NULL WHERE `CONTENT_ID` =1736 OR  `CONTENT_ID`=1737 OR `CONTENT_ID`=1738");

        $this->addSql("DELETE FROM `psa_content_category_category` WHERE `PARENT_ID` = 4 AND `CHILD_ID` = 9");

        $this->addSql("DELETE FROM `psa_content_category` WHERE `CONTENT_CATEGORY_ID` = 3");
        $this->addSql("DELETE FROM `psa_content_category` WHERE `CONTENT_CATEGORY_ID` ='10'");
        $this->addSql("DELETE FROM `psa_content_category` WHERE `CONTENT_CATEGORY_ID`='11'");
        $this->addSql("DELETE FROM `psa_content_category` WHERE `CONTENT_CATEGORY_ID` ='4'");
        $this->addSql("DELETE FROM `psa_content_category` WHERE `CONTENT_CATEGORY_ID` ='9'");

        // rollback table
        $this->addSql('DROP TABLE psa_content_category_category');
        $this->addSql('ALTER TABLE psa_content_category DROP FOREIGN KEY FK_27C3EEBC5622E2C2');
        $this->addSql('DROP INDEX IDX_27C3EEBC5622E2C2 ON psa_content_category');
        $this->addSql('ALTER TABLE psa_content_category DROP CONTENT_CATEGORY_ORDER, DROP LANGUE_ID');
    }
}
