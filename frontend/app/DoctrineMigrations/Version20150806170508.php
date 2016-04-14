<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150806170508 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE psa_appli_mobile ADD MEDIA_GOOGLEPLAY INT DEFAULT NULL, ADD MEDIA_APPLESTORE INT DEFAULT NULL, ADD MEDIA_WINDOWS INT DEFAULT NULL, CHANGE APPMOBILE_ID APPMOBILE_ID INT NOT NULL');
        $this->addSql('ALTER TABLE psa_appli_mobile ADD CONSTRAINT FK_8D97E1B314E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('ALTER TABLE psa_appli_mobile ADD CONSTRAINT FK_8D97E1B3BDF3B151 FOREIGN KEY (MEDIA_GOOGLEPLAY) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('ALTER TABLE psa_appli_mobile ADD CONSTRAINT FK_8D97E1B3546D3CF2 FOREIGN KEY (MEDIA_APPLESTORE) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('ALTER TABLE psa_appli_mobile ADD CONSTRAINT FK_8D97E1B35D0A6361 FOREIGN KEY (MEDIA_WINDOWS) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('CREATE INDEX IDX_8D97E1B314E107D9 ON psa_appli_mobile (MEDIA_ID)');
        $this->addSql('CREATE INDEX IDX_8D97E1B3BDF3B151 ON psa_appli_mobile (MEDIA_GOOGLEPLAY)');
        $this->addSql('CREATE INDEX IDX_8D97E1B3546D3CF2 ON psa_appli_mobile (MEDIA_APPLESTORE)');
        $this->addSql('CREATE INDEX IDX_8D97E1B35D0A6361 ON psa_appli_mobile (MEDIA_WINDOWS)');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE psa_appli_mobile DROP FOREIGN KEY FK_8D97E1B314E107D9');
        $this->addSql('ALTER TABLE psa_appli_mobile DROP FOREIGN KEY FK_8D97E1B3BDF3B151');
        $this->addSql('ALTER TABLE psa_appli_mobile DROP FOREIGN KEY FK_8D97E1B3546D3CF2');
        $this->addSql('ALTER TABLE psa_appli_mobile DROP FOREIGN KEY FK_8D97E1B35D0A6361');
        $this->addSql('DROP INDEX IDX_8D97E1B314E107D9 ON psa_appli_mobile');
        $this->addSql('DROP INDEX IDX_8D97E1B3BDF3B151 ON psa_appli_mobile');
        $this->addSql('DROP INDEX IDX_8D97E1B3546D3CF2 ON psa_appli_mobile');
        $this->addSql('DROP INDEX IDX_8D97E1B35D0A6361 ON psa_appli_mobile');
        $this->addSql('ALTER TABLE psa_appli_mobile DROP MEDIA_GOOGLEPLAY, DROP MEDIA_APPLESTORE, DROP MEDIA_WINDOWS, CHANGE APPMOBILE_ID APPMOBILE_ID INT AUTO_INCREMENT NOT NULL');




    }
}
