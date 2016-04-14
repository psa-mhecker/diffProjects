<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150918162650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE `psa_site_national_param` DROP FOREIGN KEY FK_5AF8612DF1B5AEBC");
        $this->addSql("ALTER TABLE `psa_site_national_param`
                        ADD CONSTRAINT `FK_5AF8612DF1B5AEBC` FOREIGN KEY (`SITE_ID`) REFERENCES `psa_site` (`SITE_ID`) 
                        ON DELETE CASCADE");
        $this->addSql("ALTER TABLE `psa_cta` DROP FOREIGN KEY FK_F0F9A976F1B5AEBC");
        $this->addSql("ALTER TABLE `psa_cta`
                        ADD CONSTRAINT `FK_F0F9A976F1B5AEBC` FOREIGN KEY (`SITE_ID`) REFERENCES `psa_site` (`SITE_ID`)
                        ON DELETE CASCADE");
        $this->addSql("ALTER TABLE `psa_site_service` DROP FOREIGN KEY FK_7A387AF4F1B5AEBC");
        $this->addSql("ALTER TABLE `psa_site_service`
                        ADD CONSTRAINT `FK_7A387AF4F1B5AEBC` FOREIGN KEY (`SITE_ID`) REFERENCES `psa_site` (`SITE_ID`)
                        ON DELETE CASCADE");    
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
