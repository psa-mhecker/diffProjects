<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150924140759 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_MANUAL_MEDIA', null, 2, null, null, 1, null),
              ('NDP_MEDIA_TYPE', null, 2, null, null, 1, null),
              ('NDP_ADD_MANUAL_MEDIA', null, 2, null, null, 1, null)
              ");

        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_MANUAL_MEDIA', 1, 1, 'Médias manuels'),
              ('NDP_MEDIA_TYPE', 1, 1, 'Type de média'),
              ('NDP_ADD_MANUAL_MEDIA', 1, 1, 'Ajouter un média')
              ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
      
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_MANUAL_MEDIA",
                "NDP_MEDIA_TYPE",
                "NDP_ADD_MANUAL_MEDIA"
                )
            ');
        }
    }
}
