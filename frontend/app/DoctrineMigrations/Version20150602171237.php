<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150602171237 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_media_format` (
            `MEDIA_FORMAT_ID`,
            `MEDIA_FORMAT_LABEL`,
            `MEDIA_FORMAT_RATIO`,
            `MEDIA_FORMAT_HEIGHT`,
            `MEDIA_FORMAT_WIDTH`,
            `MEDIA_FORMAT_COLORS`,
            `MEDIA_FORMAT_COMPLETE`
        ) VALUES
            (172, 'NDP_2_COLONNES_2/3', 1, 0, 690, '', 0),
            (173, 'NDP_2_COLONNES_1/3', 1, 0, 210, '', 0)
            "
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_media_format WHERE MEDIA_FORMAT_ID=172 ");
        $this->addSql("DELETE FROM psa_media_format WHERE MEDIA_FORMAT_ID=173 ");
    }
}
