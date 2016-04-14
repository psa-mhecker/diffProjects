<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150602104909 extends AbstractMigration
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
            (168, 'NDP_3_COLONNES', 1, 310, 286, '', 0),
            (169, 'NDP_CONTENU_ARTICLE_VISUEL_480', 1, 260, 480, '', 0),
            (170, 'NDP_CONTENU_ARTICLE_VISUEL_600', 1, 0, 600, '', 0),
            (171, 'NDP_CONTENU_ARTICLE_VISUEL_MOBILE', 1, 174, 320, '', 0)
            "
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_media_format WHERE MEDIA_FORMAT_ID=168 ");
        $this->addSql("DELETE FROM psa_media_format WHERE MEDIA_FORMAT_ID=169 ");
        $this->addSql("DELETE FROM psa_media_format WHERE MEDIA_FORMAT_ID=170 ");
        $this->addSql("DELETE FROM psa_media_format WHERE MEDIA_FORMAT_ID=171 ");
    }
}
