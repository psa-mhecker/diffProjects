<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150923155513 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_cta ADD POPIN_ACTIVE TINYINT(1) DEFAULT NULL, ADD POPIN_CONFIRMATION TINYINT(1) DEFAULT NULL, ADD POPIN_DESC LONGTEXT DEFAULT NULL, ADD POPIN_TITLE VARCHAR(255) DEFAULT NULL, ADD POPIN_TIMING VARCHAR(255) DEFAULT NULL, ADD POPIN_CANCEL VARCHAR(255) DEFAULT NULL, ADD POPIN_MEDIA INT DEFAULT NULL');
        $this->addSql('ALTER TABLE psa_cta ADD CONSTRAINT FK_F0F9A9768ABE88F2 FOREIGN KEY (POPIN_MEDIA) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('CREATE INDEX IDX_F0F9A9768ABE88F2 ON psa_cta (POPIN_MEDIA)');
         $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_TIMING', null, 2, null, null, 1, null),
              ('NDP_MANUAL_LABEL', null, 2, null, null, 1, null),
              ('NDP_MSG_BUTTON_CONFIRM', null, 2, null, null, 1, null),
              ('NDP_CANCEL_LABEL', null, 2, null, null, 1, null)
              ");

        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_TIMING', 1, 1, 'Timing'),
              ('NDP_MANUAL_LABEL', 1, 1, 'Libellé du lien manuel'),
              ('NDP_MSG_BUTTON_CONFIRM', 1, 1, 'Le libellé du bouton de confirmation de redirection est la reprise du libellé du CTA'),
              ('NDP_CANCEL_LABEL', 1, 1, 'Libellé du lien d’annulation')
              ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
      
        $this->addSql('ALTER TABLE psa_cta DROP FOREIGN KEY FK_F0F9A9768ABE88F2');        
        $this->addSql('DROP INDEX IDX_F0F9A9768ABE88F2 ON psa_cta');
        $this->addSql('ALTER TABLE psa_cta DROP POPIN_ACTIVE, DROP POPIN_CONFIRMATION, DROP POPIN_DESC, DROP POPIN_TITLE, DROP POPIN_TIMING, DROP POPIN_CANCEL, DROP POPIN_MEDIA');
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_TIMING",
                "NDP_MANUAL_LABEL",
                "NDP_MSG_BUTTON_CONFIRM",
                "NDP_CANCEL_LABEL"
                )
            ');
        }
    }
}
