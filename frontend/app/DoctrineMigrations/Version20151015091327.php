<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151015091327 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //$this->addSql('DROP TABLE psa_vehicle_category_site_order');

        //$this->addSql('CREATE TABLE psa_vehicle_category_site_order (CATEGORY_ORDER INT DEFAULT 1, ID INT NOT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_749B75F611D3633A (ID), INDEX IDX_749B75F65622E2C2 (LANGUE_ID), INDEX IDX_749B75F6F1B5AEBC (SITE_ID), PRIMARY KEY(ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_vehicle_category_site_order DROP FOREIGN KEY FK_749B75F611D3633A');
        $this->addSql('ALTER TABLE psa_vehicle_category_site_order DROP FOREIGN KEY FK_749B75F65622E2C2');
        $this->addSql('ALTER TABLE psa_vehicle_category_site_order DROP FOREIGN KEY FK_749B75F6F1B5AEBC');

        $this->addSql('ALTER TABLE psa_vehicle_category_site_order ADD CONSTRAINT FK_749B75F611D3633A FOREIGN KEY (ID) REFERENCES psa_vehicle_category (ID)  ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_vehicle_category_site_order ADD CONSTRAINT FK_749B75F65622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_vehicle_category_site_order ADD CONSTRAINT FK_749B75F6F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)  ON DELETE CASCADE');

        $this->addSql('ALTER TABLE psa_vehicle_category_site DROP FOREIGN KEY FK_92D64B465622E2C2');
        $this->addSql('ALTER TABLE psa_vehicle_category_site DROP FOREIGN KEY FK_92D64B46F1B5AEBC');

        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD CONSTRAINT FK_92D64B465622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_vehicle_category_site ADD CONSTRAINT FK_92D64B46F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID) ON DELETE CASCADE');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
