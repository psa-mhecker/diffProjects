<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160209131846 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE psa_media_format_intercept DROP FOREIGN KEY FK_MEDIA_FORMAT_INTERCEPT_11');
        $this->addSql('ALTER TABLE psa_media_format_intercept ADD CONSTRAINT FK_MEDIA_FORMAT_INTERCEPT_11 FOREIGN KEY (MEDIA_FORMAT_ID) REFERENCES psa_media_format (MEDIA_FORMAT_ID) ON DELETE CASCADE');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT=321, MEDIA_FORMAT_WIDTH=428 WHERE MEDIA_FORMAT_LABEL ="NDP_MEDIA_ONE_ARTICLE_THREE_VISUAL" ');
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT=480, MEDIA_FORMAT_WIDTH=852 WHERE MEDIA_FORMAT_LABEL ="NDP_MEDIA_MANUAL_WALL_BIG_VISUAL" ');
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT=240, MEDIA_FORMAT_WIDTH=426 WHERE MEDIA_FORMAT_LABEL ="NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL" ');
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT=646, MEDIA_FORMAT_WIDTH=1280 WHERE MEDIA_FORMAT_LABEL ="NDP_PF2_DESKTOP" ');
        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_KEEP_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_BO`, `MEDIA_FORMAT_FO`) VALUES
            ('NDP_CONTENT_4_3', 1, 330, 854, 1.33, 1, 1),
            ('NDP_CONTENT_1_3', 1, 330, 427, 1.33, 1, 1),
            ('NDP_SMALL_PICTO', 1, 95, 95, 1, 1, 1)
        ");

        $this->addSql('DELETE FROM psa_media_format WHERE MEDIA_FORMAT_LABEL IN(
              "NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL_MOBILE",
              "NDP_MEDIA_ONE_ARTICLE_TWO_VISUAL",
              "NDP_MEDIA_CONTENT_TWO_COLUMN",
              "NDP_MEDIA_MANUAL_WALL_BIG_VISUAL_MOBILE",
              "NDP_MEDIA_CONTENT_ONE_COLUMN_MOBILE",
              "NDP_MEDIA_CONTENT_TWO_COLUMN_MOBILE"
              )');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT=0, MEDIA_FORMAT_WIDTH=425 WHERE MEDIA_FORMAT_LABEL ="NDP_MEDIA_ONE_ARTICLE_THREE_VISUAL" ');
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT=0, MEDIA_FORMAT_WIDTH=854 WHERE MEDIA_FORMAT_LABEL ="NDP_MEDIA_MANUAL_WALL_BIG_VISUAL" ');
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT=0, MEDIA_FORMAT_WIDTH=426 WHERE MEDIA_FORMAT_LABEL ="NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL" ');
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT=600, MEDIA_FORMAT_WIDTH=1280 WHERE MEDIA_FORMAT_LABEL ="NDP_PF2_DESKTOP" ');
        $this->addSql('DELETE FROM psa_media_format WHERE MEDIA_FORMAT_LABEL IN("NDP_CONTENT_4_3", "NDP_CONTENT_4_3", "NDP_SMALL_PICTO")');
        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_KEEP_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_BO`, `MEDIA_FORMAT_FO`) VALUES
            ('NDP_MEDIA_MANUAL_WALL_BIG_VISUAL_MOBILE', 1, 180, 320, 1.78, 0, 1),
            ('NDP_MEDIA_ONE_ARTICLE_TWO_VISUAL', 1, 0, 638, 1.49, 1, 1),
            ('NDP_MEDIA_CONTENT_TWO_COLUMN_MOBILE', 1, 214, 640, 1.49, 0, 1),
            ('NDP_MEDIA_CONTENT_TWO_COLUMN', 1, 0, 638, 1.49, 1, 1),
            ('NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL_MOBILE', 1, 180, 320, 1.78, 0, 1)
        ");
    }
}
