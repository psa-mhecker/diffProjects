<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150827141953 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('TRUNCATE TABLE  `psa_groupe_reseaux_sociaux`');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux DROP PRIMARY KEY, CHANGE GROUPE_RESEAUX_SOCIAUX_ID GROUPE_RESEAUX_SOCIAUX_ID int(11)');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux ADD LANGUE_ID INT NOT NULL, ADD SITE_ID INT NOT NULL, ADD RESEAU_SOCIAL_ID INT NOT NULL, ADD GROUPE_ORDER INT NOT NULL, DROP GROUPE_RESEAUX_SOCIAUX_ID, DROP LANGUE_ID, DROP SITE_ID, DROP GROUPE_RESEAUX_SOCIAUX_LABEL, DROP GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA, DROP GROUPE_RESEAUX_SOCIAUX_DEFAUT_PUBLIC');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux ADD CONSTRAINT FK_5BA09A2B1ECAB220 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID) ON DELETE CASCADE ON UPDATE NO ACTION');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux ADD CONSTRAINT FK_5BA09A2BF01EACE5 FOREIGN KEY (RESEAU_SOCIAL_ID) REFERENCES psa_reseau_social (RESEAU_SOCIAL_ID) ON DELETE CASCADE ON UPDATE NO ACTION');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux ADD CONSTRAINT FK_5BA09A2B92C31242 FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID) ON DELETE CASCADE ON UPDATE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5BA09A2B1ECAB220 ON psa_groupe_reseaux_sociaux (LANGUE_ID)');
        $this->addSql('CREATE INDEX IDX_5BA09A2B92C31242 ON psa_groupe_reseaux_sociaux (SITE_ID)');
        $this->addSql('CREATE INDEX IDX_5BA09A2BF01EACE5 ON psa_groupe_reseaux_sociaux (RESEAU_SOCIAL_ID)');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux ADD PRIMARY KEY (LANGUE_ID, SITE_ID, RESEAU_SOCIAL_ID)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('TRUNCATE TABLE  `psa_groupe_reseaux_sociaux`');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux DROP FOREIGN KEY FK_5BA09A2B1ECAB220');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux DROP FOREIGN KEY FK_5BA09A2B92C31242');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux DROP FOREIGN KEY FK_5BA09A2BF01EACE5');
        $this->addSql('DROP INDEX IDX_5BA09A2B1ECAB220 ON psa_groupe_reseaux_sociaux');
        $this->addSql('DROP INDEX IDX_5BA09A2B92C31242 ON psa_groupe_reseaux_sociaux');
        $this->addSql('DROP INDEX IDX_5BA09A2BF01EACE5 ON psa_groupe_reseaux_sociaux');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux ADD GROUPE_RESEAUX_SOCIAUX_ID INT NOT NULL, ADD LANGUE_ID INT NOT NULL, ADD SITE_ID INT NOT NULL, ADD GROUPE_RESEAUX_SOCIAUX_LABEL VARCHAR(255) NOT NULL COLLATE utf8_swedish_ci, ADD GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA INT DEFAULT 0 NOT NULL, ADD GROUPE_RESEAUX_SOCIAUX_DEFAUT_PUBLIC INT DEFAULT 0 NOT NULL, DROP LANGUE_ID, DROP SITE_ID, DROP RESEAU_SOCIAL_ID, DROP GROUPE_ORDER');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux ADD PRIMARY KEY (GROUPE_RESEAUX_SOCIAUX_ID, LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_groupe_reseaux_sociaux CHANGE GROUPE_RESEAUX_SOCIAUX_ID GROUPE_RESEAUX_SOCIAUX_ID INT AUTO_INCREMENT NOT NULL');
    }
}
