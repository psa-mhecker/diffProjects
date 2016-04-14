<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150424185748 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta_cta DROP FOREIGN KEY FK_F83E37E1DF977A');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta_cta ADD CONSTRAINT FK_F83E37E1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta DROP FOREIGN KEY FK_59FECE2CE1DF977A');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta ADD CONSTRAINT FK_59FECE2CE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_content_version_cta DROP FOREIGN KEY FK_A5107385E1DF977A');
        $this->addSql('ALTER TABLE psa_content_version_cta ADD CONSTRAINT FK_A5107385E1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_zone_cta DROP FOREIGN KEY FK_B03DE77CE1DF977A');
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta DROP FOREIGN KEY FK_BDC5C30EE1DF977A');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta ADD CONSTRAINT FK_BDC5C30EE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta DROP FOREIGN KEY FK_A5907A9EE1DF977A');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta ADD CONSTRAINT FK_A5907A9EE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta DROP FOREIGN KEY FK_605F4639E1DF977A');
        $this->addSql('ALTER TABLE psa_page_multi_zone_multi_cta ADD CONSTRAINT FK_605F4639E1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_content_version_cta_cta DROP FOREIGN KEY FK_E9138D9BE1DF977A');
        $this->addSql('ALTER TABLE psa_content_version_cta_cta ADD CONSTRAINT FK_E9138D9BE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta DROP FOREIGN KEY FK_6000D602E1DF977A');
        $this->addSql('ALTER TABLE psa_page_zone_multi_cta_cta ADD CONSTRAINT FK_6000D602E1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta DROP FOREIGN KEY FK_F936EBBDE1DF977A');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta ADD CONSTRAINT FK_F936EBBDE1DF977A FOREIGN KEY (CTA_ID) REFERENCES psa_cta (ID)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}
