<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151112145646 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_media_format` SET `MEDIA_FORMAT_WIDTH` = 1280 WHERE `MEDIA_FORMAT_ID` = 41");
        $this->addSql("UPDATE `psa_media_format` SET `MEDIA_FORMAT_WIDTH` = 639 WHERE `MEDIA_FORMAT_ID` = 52");
        $this->addSql("UPDATE `psa_media_format` SET `MEDIA_FORMAT_WIDTH` = 426 WHERE `MEDIA_FORMAT_ID` = 55");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_media_format` SET `MEDIA_FORMAT_WIDTH` = 1040 WHERE `MEDIA_FORMAT_ID` = 41");
        $this->addSql("UPDATE `psa_media_format` SET `MEDIA_FORMAT_WIDTH` = 519 WHERE `MEDIA_FORMAT_ID` = 52");
        $this->addSql("UPDATE `psa_media_format` SET `MEDIA_FORMAT_WIDTH` = 345 WHERE `MEDIA_FORMAT_ID` = 55");
    }
}
