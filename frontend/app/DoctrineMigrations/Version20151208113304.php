<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151208113304 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_MEDIA_CONTENT_ONE_COLUMN' => array(
                    'expression' => 'Contenu 1 colonne',
                    'bo'=>1
                ),
                'NDP_MEDIA_CONTENT_ONE_COLUMN_MOBILE' => array(
                    'expression' => 'Contenu 1 colonne mobile',
                    'bo'=>1
                ),
                'NDP_MEDIA_CONTENT_TWO_COLUMN' => array(
                    'expression' => 'Contenu 2 colonnes',
                    'bo'=>1
                ),
                'NDP_MEDIA_CONTENT_TWO_COLUMN_MOBILE' => array(
                    'expression' => 'Contenu 2 colonnes mobile',
                    'bo'=>1
                ),
                'NDP_MEDIA_ONE_ARTICLE_TWO_VISUAL' => array(
                    'expression' => 'Contenu 1 article et 2 visuels',
                    'bo'=>1
                ),
                'NDP_MEDIA_ONE_ARTICLE_THREE_VISUAL' => array(
                    'expression' => 'Contenu 1 article et 3 visuels',
                    'bo'=>1
                ),
                'NDP_MEDIA_MANUAL_WALL_BIG_VISUAL' => array(
                    'expression' => 'Mur média manuel grand visuel',
                    'bo'=>1
                ),
                'NDP_MEDIA_MANUAL_WALL_BIG_VISUAL_MOBILE' => array(
                    'expression' => 'Mur média manuel grand visuel mobile',
                    'bo'=>1
                ),
                'NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL' => array(
                    'expression' => 'Mur média manuel petit visuel',
                    'bo'=>1
                ),
                'NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL_MOBILE' => array(
                    'expression' => 'Mur média manuel petit visuel mobile',
                    'bo'=>1
                ),
            )
        );

        $this->addSql('TRUNCATE psa_content_zone_media');
        $this->addSql('TRUNCATE psa_page_multi_zone_media');
        $this->addSql('TRUNCATE psa_page_zone_media');
        $this->addSql('TRUNCATE psa_media_format_intercept');
        $this->addSql('DELETE FROM psa_media_format');
        $this->addSql('ALTER TABLE psa_media_format AUTO_INCREMENT = 1');
        $this->addSql('ALTER TABLE psa_media_format CHANGE MEDIA_FORMAT_RATIO MEDIA_FORMAT_KEEP_RATIO INT NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE psa_media_format ADD MEDIA_FORMAT_RATIO FLOAT(7,2) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE psa_media_format ADD MEDIA_FORMAT_BO INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE psa_media_format ADD MEDIA_FORMAT_FO INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE psa_media_format DROP MEDIA_FORMAT_COLORS');
        $this->addSql('ALTER TABLE psa_media_format DROP MEDIA_FORMAT_COMPLETE');
        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_KEEP_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_BO`, `MEDIA_FORMAT_FO`) VALUES
            ('NDP_MEDIA_CONTENT_ONE_COLUMN', 1, 0, 1280, 2.98, 1, 1),
            ('NDP_MEDIA_CONTENT_ONE_COLUMN_MOBILE',  1, 214, 640, 2.98, 0, 1),
            ('NDP_MEDIA_CONTENT_TWO_COLUMN', 1, 0, 638, 1.49, 1, 1),
            ('NDP_MEDIA_CONTENT_TWO_COLUMN_MOBILE', 1, 214, 640, 1.49, 0, 1),
            ('NDP_MEDIA_ONE_ARTICLE_TWO_VISUAL', 1, 0, 638, 1.49, 1, 1),
            ('NDP_MEDIA_ONE_ARTICLE_THREE_VISUAL', 1, 0, 425, 0.99, 1, 1),
            ('NDP_MEDIA_MANUAL_WALL_BIG_VISUAL', 1, 0, 854, 1.78, 1, 1),
            ('NDP_MEDIA_MANUAL_WALL_BIG_VISUAL_MOBILE', 1, 180, 320, 1.78, 0, 1),
            ('NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL', 1, 0, 426, 1.78, 1, 1),
            ('NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL_MOBILE', 1, 180, 320, 1.78, 0, 1)
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_MEDIA_CONTENT_ONE_COLUMN',
            'NDP_MEDIA_CONTENT_ONE_COLUMN_MOBILE',
            'NDP_MEDIA_CONTENT_TWO_COLUMN',
            'NDP_MEDIA_CONTENT_TWO_COLUMN_MOBILE',
            'NDP_MEDIA_ONE_ARTICLE_TWO_VISUAL',
            'NDP_MEDIA_ONE_ARTICLE_THREE_VISUAL',
            'NDP_MEDIA_MANUAL_WALL_BIG_VISUAL',
            'NDP_MEDIA_MANUAL_WALL_BIG_VISUAL_MOBILE',
            'NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL',
            'NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL_MOBILE',
        ));

        $this->addSql('TRUNCATE psa_content_zone_media');
        $this->addSql('TRUNCATE psa_page_multi_zone_media');
        $this->addSql('TRUNCATE psa_page_zone_media');
        $this->addSql('TRUNCATE psa_media_format_intercept');
        $this->addSql('DELETE FROM psa_media_format');
        $this->addSql('ALTER TABLE psa_media_format AUTO_INCREMENT = 1');

        $this->addSql('ALTER TABLE psa_media_format DROP MEDIA_FORMAT_RATIO');
        $this->addSql('ALTER TABLE psa_media_format DROP MEDIA_FORMAT_BO');
        $this->addSql('ALTER TABLE psa_media_format DROP MEDIA_FORMAT_FO');
        $this->addSql('ALTER TABLE psa_media_format ADD MEDIA_FORMAT_COLORS VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE psa_media_format ADD MEDIA_FORMAT_COMPLETE INT DEFAULT 0');
        $this->addSql('ALTER TABLE psa_media_format CHANGE MEDIA_FORMAT_KEEP_RATIO MEDIA_FORMAT_RATIO INT NOT NULL DEFAULT 0');
        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_COLORS`, `MEDIA_FORMAT_COMPLETE`) VALUE ('Vignette', 1, 45, 60, '0', 0)");
    }
}
