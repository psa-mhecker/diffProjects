<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150506183342 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_vehicle_category (ID INT AUTO_INCREMENT NOT NULL, LABEL_CENTRAL VARCHAR(255) NOT NULL, MEDIA_ID INT NOT NULL, INDEX IDX_A8660BC714E107D9 (MEDIA_ID), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_vehicle_category ADD CONSTRAINT FK_A8660BC714E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID)');

        $this->addSql('CREATE TABLE psa_vehicle_category_site (`LABEL` VARCHAR(255) NOT NULL, CRITERES_MARKETING VARCHAR(255) NOT NULL, CATEGORY_ORDER INT DEFAULT 1, ID INT NOT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_92D64B4611D3633A (ID), INDEX IDX_92D64B465622E2C2 (LANGUE_ID), INDEX IDX_92D64B46F1B5AEBC (SITE_ID), PRIMARY KEY(ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD CONSTRAINT FK_92D64B4611D3633A FOREIGN KEY (ID) REFERENCES psa_vehicle_category (ID)');
        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD CONSTRAINT FK_92D64B465622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD CONSTRAINT FK_92D64B46F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');



    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_vehicle_category_site DROP FOREIGN KEY FK_92D64B4611D3633A');
        $this->addSql('DROP TABLE psa_vehicle_category_site');
        $this->addSql('DROP TABLE psa_vehicle_category');

    }
}
