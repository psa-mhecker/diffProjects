<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150707141530 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('TRUNCATE TABLE psa_model');
        // renommer la table model en mode site
        $this->addSql('RENAME TABLE psa_model TO psa_model_site');

        // creation de la nouvelle table psa model
        $this->addSql('CREATE TABLE psa_model (LCDV4 VARCHAR(4) NOT NULL, GENDER VARCHAR(2) NOT NULL, MODEL VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_F5DB39BF68469EFD (LCDV4), PRIMARY KEY(LCDV4)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_model_site MODIFY ID INT NOT NULL');
        $this->addSql('ALTER TABLE psa_model_site DROP PRIMARY KEY');
        $this->addSql('DROP INDEX UNIQUE_MODEL_PER_SITE ON psa_model_site');
        $this->addSql('ALTER TABLE psa_model_site DROP FOREIGN KEY FK_F5DB39BF5622E2C2');
        $this->addSql('ALTER TABLE psa_model_site DROP FOREIGN KEY FK_F5DB39BFF1B5AEBC');
        $this->addSql('ALTER TABLE psa_model_site DROP ID, DROP GENDER, DROP MODEL');
        $this->addSql('ALTER TABLE psa_model_site ADD CONSTRAINT FK_C3C59E3868469EFD FOREIGN KEY (LCDV4) REFERENCES psa_model (LCDV4)');

        $this->addSql('CREATE INDEX IDX_C3C59E3868469EFD ON psa_model_site (LCDV4)');

        $this->addSql('ALTER TABLE psa_model_site ADD PRIMARY KEY (LCDV4, SITE_ID, LANGUE_ID)');

        $this->addSql('DROP INDEX idx_f5db39bff1b5aebc ON psa_model_site');
        $this->addSql('CREATE INDEX IDX_C3C59E38F1B5AEBC ON psa_model_site (SITE_ID)');

        $this->addSql('DROP INDEX IDX_F5DB39BF5622E2C2 ON psa_model_site');
        $this->addSql('CREATE INDEX IDX_C3C59E385622E2C2 ON psa_model_site (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_model_site ADD CONSTRAINT FK_F5DB39BF5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_model_site ADD CONSTRAINT FK_F5DB39BFF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        // empty table before create columns with not null constraint ..
        $this->addSql('TRUNCATE TABLE psa_model_site');
        //  remake index and
        $this->addSql('ALTER TABLE psa_model_site DROP FOREIGN KEY FK_F5DB39BFF1B5AEBC');
        $this->addSql('ALTER TABLE psa_model_site DROP FOREIGN KEY FK_F5DB39BF5622E2C2');
        $this->addSql('DROP INDEX IDX_C3C59E385622E2C2 ON psa_model_site');
        $this->addSql('CREATE INDEX IDX_F5DB39BF5622E2C2 ON psa_model_site (LANGUE_ID)');

        $this->addSql('DROP INDEX IDX_C3C59E38F1B5AEBC ON psa_model_site');
        $this->addSql('CREATE INDEX idx_f5db39bff1b5aebc ON psa_model_site (SITE_ID)');

        $this->addSql('ALTER TABLE psa_model_site DROP PRIMARY KEY');


        $this->addSql('ALTER TABLE psa_model_site DROP FOREIGN KEY FK_C3C59E3868469EFD');
        $this->addSql('DROP INDEX IDX_C3C59E3868469EFD ON psa_model_site');
        $this->addSql('ALTER TABLE psa_model_site ADD ID INT NOT NULL, ADD GENDER VARCHAR(2) NOT NULL COLLATE utf8_unicode_ci, ADD MODEL VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE psa_model_site ADD CONSTRAINT FK_F5DB39BFF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_model_site ADD CONSTRAINT FK_F5DB39BF5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');

        $this->addSql('CREATE UNIQUE INDEX UNIQUE_MODEL_PER_SITE ON psa_model_site (SITE_ID, LANGUE_ID, LCDV4)');
        $this->addSql('ALTER TABLE psa_model_site ADD PRIMARY KEY (ID)');
        $this->addSql('ALTER TABLE psa_model_site MODIFY ID INT AUTO_INCREMENT NOT NULL');


///

        // drop table psa model
        $this->addSql('DROP TABLE psa_model');
        // renommer la table model en mode site
        $this->addSql('RENAME TABLE psa_model_site TO psa_model');

    }
}
