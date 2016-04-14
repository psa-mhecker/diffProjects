<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150216094929 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_appmobile (APPMOBILE_ID INT AUTO_INCREMENT NOT NULL, APPMOBILE_LABEL VARCHAR(255) NOT NULL, APPMOBILE_LABEL_BO VARCHAR(255) NOT NULL, APPMOBILE_TEXTE LONGTEXT NOT NULL, APPMOBILE_URL_VISUEL VARCHAR(255) NOT NULL, APPMOBILE_MODE_OUVERTURE VARCHAR(25) NOT NULL, APPMOBILE_URL_GOOGLEPLAY VARCHAR(255) NOT NULL, APPMOBILE_URL_APPLESTORE VARCHAR(255) NOT NULL, APPMOBILE_URL_WINDOWS VARCHAR(255) NOT NULL, SITE_ID INT DEFAULT NULL, LANGUE_ID INT DEFAULT NULL, MEDIA_ID INT DEFAULT NULL, INDEX IDX_B980AB20F1B5AEBC (SITE_ID), INDEX IDX_B980AB205622E2C2 (LANGUE_ID), INDEX IDX_B980AB2014E107D9 (MEDIA_ID), PRIMARY KEY(APPMOBILE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_appmobile ADD CONSTRAINT FK_B980AB20F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_appmobile ADD CONSTRAINT FK_B980AB205622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_appmobile ADD CONSTRAINT FK_B980AB2014E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE psa_appmobile');
    }
}
