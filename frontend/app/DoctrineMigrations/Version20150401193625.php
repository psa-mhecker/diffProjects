<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401193625 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        // drop old FK
        $this->addSql('ALTER TABLE psa_page_zone_cta DROP FOREIGN KEY FK_B03DE77CB4EDB1E5622E2C229381310F15EAE15');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta DROP FOREIGN KEY FK_A5907A9EB4EDB1E5622E2C229381310F15EAE15');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta DROP FOREIGN KEY FK_BDC5C30EB4EDB1E5622E2C229381310BA07F8665F342437');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta DROP FOREIGN KEY FK_F936EBBDB4EDB1E5622E2C229381310BA07F8665F342437');
        //Recreate with DELETE CASCADE
        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CB4EDB1E5622E2C229381310F15EAE15 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID) REFERENCES psa_page_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta ADD CONSTRAINT FK_A5907A9EB4EDB1E5622E2C229381310F15EAE15 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID) REFERENCES psa_page_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta ADD CONSTRAINT FK_BDC5C30EB4EDB1E5622E2C229381310BA07F8665F342437 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER) REFERENCES psa_page_multi_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta ADD CONSTRAINT FK_F936EBBDB4EDB1E5622E2C229381310BA07F8665F342437 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER) REFERENCES psa_page_multi_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER) ON DELETE CASCADE');
   }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_page_zone_cta DROP FOREIGN KEY FK_B03DE77CB4EDB1E5622E2C229381310F15EAE15');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta DROP FOREIGN KEY FK_A5907A9EB4EDB1E5622E2C229381310F15EAE15');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta DROP FOREIGN KEY FK_BDC5C30EB4EDB1E5622E2C229381310BA07F8665F342437');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta DROP FOREIGN KEY FK_F936EBBDB4EDB1E5622E2C229381310BA07F8665F342437');

        $this->addSql('ALTER TABLE psa_page_zone_cta ADD CONSTRAINT FK_B03DE77CB4EDB1E5622E2C229381310F15EAE15 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID) REFERENCES psa_page_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID)');
        $this->addSql('ALTER TABLE psa_page_zone_cta_cta ADD CONSTRAINT FK_A5907A9EB4EDB1E5622E2C229381310F15EAE15 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID) REFERENCES psa_page_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, ZONE_TEMPLATE_ID)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta ADD CONSTRAINT FK_BDC5C30EB4EDB1E5622E2C229381310BA07F8665F342437 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER) REFERENCES psa_page_multi_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER)');
        $this->addSql('ALTER TABLE psa_page_multi_zone_cta_cta ADD CONSTRAINT FK_F936EBBDB4EDB1E5622E2C229381310BA07F8665F342437 FOREIGN KEY (PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER) REFERENCES psa_page_multi_zone (PAGE_ID, LANGUE_ID, PAGE_VERSION, AREA_ID, ZONE_ORDER)');
  }
}
