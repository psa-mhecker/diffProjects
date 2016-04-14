<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160330090042 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // cleaning rewrites !!
        $this->addSql('DELETE r.* FROM psa_rewrite r LEFT JOIN psa_page p ON p.PAGE_ID=r.PAGE_ID AND p.LANGUE_ID=r.LANGUE_ID WHERE p.PAGE_ID IS NULL');
        // adding row and changing index
        $this->addSql('ALTER TABLE psa_rewrite DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_rewrite DROP FOREIGN KEY FK_REWRITE_10');
        $this->addSql('ALTER TABLE psa_rewrite DROP FOREIGN KEY FK_REWRITE_11');
        $this->addSql('ALTER TABLE psa_rewrite ADD EXTERNAL_URL VARCHAR(255) DEFAULT NULL, CHANGE REWRITE_URL REWRITE_URL VARCHAR(255) NOT NULL, CHANGE SITE_ID SITE_ID INT NOT NULL, CHANGE LANGUE_ID LANGUE_ID INT NOT NULL, CHANGE REWRITE_ORDER REWRITE_ORDER INT NOT NULL, CHANGE REWRITE_ID REWRITE_ID INT NOT NULL');
        $this->addSql('ALTER TABLE psa_rewrite ADD CONSTRAINT FK_D7427BA0B4EDB1E5622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID) REFERENCES psa_page (PAGE_ID, LANGUE_ID)');
        $this->addSql('CREATE INDEX IDX_D7427BA0B4EDB1E5622E2C2 ON psa_rewrite (PAGE_ID, LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_rewrite ADD PRIMARY KEY (REWRITE_URL, LANGUE_ID, SITE_ID)');
        $this->addSql('DROP INDEX langue_id ON psa_rewrite');
        $this->addSql('CREATE INDEX IDX_D7427BA05622E2C2 ON psa_rewrite (LANGUE_ID)');
        $this->addSql('DROP INDEX site_id ON psa_rewrite');
        $this->addSql('CREATE INDEX IDX_D7427BA0F1B5AEBC ON psa_rewrite (SITE_ID)');
        $this->addSql('ALTER TABLE psa_rewrite ADD CONSTRAINT FK_REWRITE_10 FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_rewrite ADD CONSTRAINT FK_REWRITE_11 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $this->addSql('ALTER TABLE psa_rewrite DROP FOREIGN KEY FK_D7427BA0B4EDB1E5622E2C2');
        $this->addSql('DROP INDEX IDX_D7427BA0B4EDB1E5622E2C2 ON psa_rewrite');
        $this->addSql('ALTER TABLE psa_rewrite DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE psa_rewrite DROP FOREIGN KEY FK_REWRITE_10');
        $this->addSql('ALTER TABLE psa_rewrite DROP FOREIGN KEY FK_REWRITE_11');
        $this->addSql('ALTER TABLE psa_rewrite DROP EXTERNAL_URL, CHANGE REWRITE_URL REWRITE_URL VARCHAR(255) DEFAULT \'\' NOT NULL COLLATE utf8_swedish_ci, CHANGE SITE_ID SITE_ID INT DEFAULT 0 NOT NULL, CHANGE REWRITE_ORDER REWRITE_ORDER INT DEFAULT 1 NOT NULL, CHANGE REWRITE_ID REWRITE_ID INT DEFAULT NULL, CHANGE LANGUE_ID LANGUE_ID INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE psa_rewrite ADD PRIMARY KEY (REWRITE_URL, SITE_ID, LANGUE_ID)');
        $this->addSql('DROP INDEX idx_d7427ba0f1b5aebc ON psa_rewrite');
        $this->addSql('CREATE INDEX SITE_ID ON psa_rewrite (SITE_ID)');
        $this->addSql('DROP INDEX idx_d7427ba05622e2c2 ON psa_rewrite');
        $this->addSql('CREATE INDEX LANGUE_ID ON psa_rewrite (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_rewrite ADD CONSTRAINT FK_REWRITE_10 FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_rewrite ADD CONSTRAINT FK_REWRITE_11 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
    }
}

