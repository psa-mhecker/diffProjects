<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150615130855 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE psa_model (
                ID INT NOT NULL,
                LCDV4 VARCHAR(4) NOT NULL,
                GENDER VARCHAR(2) NOT NULL,
                MODEL VARCHAR(50) NOT NULL,
                SLOGAN VARCHAR(255) DEFAULT NULL,
                FINISHING_ORDER VARCHAR(10) DEFAULT NULL,
                SITE_ID INT NOT NULL,
                LANGUE_ID INT NOT NULL,
                INDEX IDX_F5DB39BFF1B5AEBC (SITE_ID),
                INDEX IDX_F5DB39BF5622E2C2 (LANGUE_ID),
                PRIMARY KEY(ID)
            )
            DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_model ADD CONSTRAINT FK_F5DB39BFF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_model ADD CONSTRAINT FK_F5DB39BF5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');



    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE psa_model');
     }
}
