<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150408163005 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        // fix pc33 multi zone multi
        $this->addSql("ALTER TABLE psa_page_multi_zone_multi ADD CONTENT_ID INT DEFAULT NULL");
        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_ID`, `MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_COLORS`, `MEDIA_FORMAT_COMPLETE`) VALUES (166, 'NDP_UN_TIERS', 1, 202, 808, '', 0);");
      
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE psa_page_multi_zone_multi DROP CONTENT_ID");
        $this->addSql("DELETE FROM psa_media_format WHERE MEDIA_FORMAT_ID=166 ");
    }
}
