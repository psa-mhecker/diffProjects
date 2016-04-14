<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160104154214 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE `psa_after_sale_services`');
        $this->addSql('DROP TABLE `psa_filter_after_sale_services`');
        $this->addSql('CREATE TABLE psa_filter_after_sale_services (ID INT NOT NULL, `LABEL` VARCHAR(50) NOT NULL, FILTER_ORDER INT NOT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_FF3D47BD5622E2C2 (LANGUE_ID), INDEX IDX_FF3D47BDF1B5AEBC (SITE_ID), PRIMARY KEY(ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_after_sale_services (ID INT NOT NULL, `LABEL` VARCHAR(255) NOT NULL, TYPE_LABEL_LINK INT NOT NULL, LABEL_LINK VARCHAR(255) NOT NULL, URL VARCHAR(255) NOT NULL, COLUMN_NUMBER INT NOT NULL, LEGAL_NOTICE VARCHAR(10) NOT NULL, PRICE_POSITION INT NOT NULL, TYPE_LABEL_PRICE INT NOT NULL, PRICE_LABEL VARCHAR(255) NOT NULL, PRICE DOUBLE PRECISION NOT NULL, DESCRIPTION VARCHAR(255) NOT NULL, TYPE_LABEL_PRICE2 INT, PRICE_LABEL2 VARCHAR(255), PRICE2 DOUBLE PRECISION, DESCRIPTION2 VARCHAR(255), LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, MEDIA_ID INT NOT NULL, MEDIA_ID2 INT NOT NULL, INDEX IDX_4FFBF98B5622E2C2 (LANGUE_ID), INDEX IDX_4FFBF98BF1B5AEBC (SITE_ID), INDEX IDX_4FFBF98B14E107D9 (MEDIA_ID), INDEX IDX_4FFBF98BE5CE357A (MEDIA_ID2), PRIMARY KEY(ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_after_sale_services_filters_relation (AFTER_SALE_SERVICES_ID INT NOT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, FILTERS_ID INT NOT NULL, INDEX IDX_B6E61801E50129B05622E2C2F1B5AEBC (AFTER_SALE_SERVICES_ID, LANGUE_ID, SITE_ID), INDEX IDX_B6E6180158A30FBB5622E2C2F1B5AEBC (FILTERS_ID, LANGUE_ID, SITE_ID), PRIMARY KEY(AFTER_SALE_SERVICES_ID, LANGUE_ID, SITE_ID, FILTERS_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_filter_after_sale_services ADD CONSTRAINT FK_FF3D47BD5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_filter_after_sale_services ADD CONSTRAINT FK_FF3D47BDF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services ADD CONSTRAINT FK_4FFBF98B5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services ADD CONSTRAINT FK_4FFBF98BF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services ADD CONSTRAINT FK_4FFBF98B14E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services ADD CONSTRAINT FK_4FFBF98BE5CE357A FOREIGN KEY (MEDIA_ID2) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation ADD CONSTRAINT FK_B6E61801E50129B05622E2C2F1B5AEBC FOREIGN KEY (AFTER_SALE_SERVICES_ID, LANGUE_ID, SITE_ID) REFERENCES psa_after_sale_services (ID, LANGUE_ID, SITE_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation ADD CONSTRAINT FK_B6E6180158A30FBB5622E2C2F1B5AEBC FOREIGN KEY (FILTERS_ID, LANGUE_ID, SITE_ID) REFERENCES psa_filter_after_sale_services (ID, LANGUE_ID, SITE_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation DROP FOREIGN KEY FK_B6E6180158A30FBB5622E2C2F1B5AEBC');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation DROP FOREIGN KEY FK_B6E61801E50129B05622E2C2F1B5AEBC');
        $this->addSql('DROP TABLE psa_after_sale_services_filters_relation');
    }
}
