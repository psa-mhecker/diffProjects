<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151019162055 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_sites_et_webservices_psa DROP FOREIGN KEY FK_A97E8AA3F1B5AEBC');
        $this->addSql('DROP INDEX IDX_A97E8AA3F1B5AEBC ON psa_sites_et_webservices_psa');
        $this->addSql('ALTER TABLE psa_sites_et_webservices_psa ADD SITE_RANGE_MANAGER VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE psa_sites_et_webservices_psa ADD CONSTRAINT FK_19F6F2DDF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID) ON DELETE CASCADE');


        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_BO_RANGE_MANAGER', null, 2, null, null, 1, null),
              ('NDP_MSG_RANGE_MANAGER_LINK_MISSING', null, 2, null, null, 1, null),
              ('NDP_MSG_RANGE_MANAGER_LINK', null, 2, null, null, 1, null)
        ");

        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_BO_RANGE_MANAGER', 1, 1, 'Url du Gestionnaire de gamme'),
              ('NDP_MSG_RANGE_MANAGER_LINK_MISSING', 1, 1, 'Vous devez saisir l\'url du gestionnaire de gamme dans le BO \"Configuration des sites PSA\" '),
              ('NDP_MSG_RANGE_MANAGER_LINK_MISSING', 1, 2, 'You should configure Range manager url before use it '),
              ('NDP_MSG_RANGE_MANAGER_LINK', 1, 1, 'Si la page ne s\'ouvre pas, vous pouvez ouvrir la page avec le lien ci-dessous :'),
              ('NDP_MSG_RANGE_MANAGER_LINK', 1, 2, 'If page does not open click, link below :'),
              ('NDP_BO_RANGE_MANAGER', 1, 2, 'Ranage Manager Url')
              ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_BO_RANGE_MANAGER",
                "NDP_MSG_RANGE_MANAGER_LINK_MISSING",
                "NDP_MSG_RANGE_MANAGER_LINK"
                )
            ');
        }

        $this->addSql('ALTER TABLE psa_sites_et_webservices_psa DROP FOREIGN KEY FK_19F6F2DDF1B5AEBC');
        $this->addSql('ALTER TABLE psa_sites_et_webservices_psa DROP SITE_RANGE_MANAGER');
        $this->addSql('ALTER TABLE psa_sites_et_webservices_psa ADD CONSTRAINT FK_A97E8AA3F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('CREATE INDEX IDX_A97E8AA3F1B5AEBC ON psa_sites_et_webservices_psa (SITE_ID)');

    }
}
