<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150812133305 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $tablesWithMediaId = $this->connection->fetchAll("SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'psa-ndp' AND COLUMN_NAME = 'MEDIA_ID'");
        foreach ($tablesWithMediaId as $key => $table) {
           $this->addSql('UPDATE '.$table['TABLE_NAME'].' t 
                LEFT JOIN psa_media s ON s.MEDIA_ID = t.MEDIA_ID
                SET t.MEDIA_ID = NULL
                WHERE s.MEDIA_ID IS NULL;
            ');
        }
        $tablesWithMediaId = $this->connection->fetchAll("SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'psa-ndp' AND COLUMN_NAME = 'MEDIA_ID2'");
        foreach ($tablesWithMediaId as $key => $table) {
           $this->addSql('UPDATE '.$table['TABLE_NAME'].' t
                LEFT JOIN psa_media s ON s.MEDIA_ID = t.MEDIA_ID2
                SET t.MEDIA_ID2 = NULL
                WHERE s.MEDIA_ID IS NULL;
            ');
       }
       $this->addSql('ALTER TABLE psa_reseau_social ADD CONSTRAINT FK_516E233D14E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID)');
       $this->addSql('CREATE INDEX IDX_516E233D14E107D9 ON psa_reseau_social (MEDIA_ID)');
       $this->addSql('ALTER TABLE psa_reseau_social ADD CONSTRAINT FK_516E233DE5CE357A FOREIGN KEY (MEDIA_ID2) REFERENCES psa_media (MEDIA_ID)');
       $this->addSql('CREATE INDEX IDX_516E233DE5CE357A ON psa_reseau_social (MEDIA_ID2)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_reseau_social DROP FOREIGN KEY FK_516E233DE5CE357A');
        $this->addSql('ALTER TABLE psa_reseau_social DROP FOREIGN KEY FK_516E233D14E107D9');
        $this->addSql('DROP INDEX IDX_516E233DE5CE357A ON psa_reseau_social');
        $this->addSql('DROP INDEX IDX_516E233D14E107D9 ON psa_reseau_social');
    }
}
