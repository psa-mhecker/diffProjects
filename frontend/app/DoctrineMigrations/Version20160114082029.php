<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160114082029 extends AbstractPsaMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array(
                'NDP_MURMEDIA_SMALL_16_9' => array(
                    'expression' => 'Mur media petit 16/9',
                    'bo'=>1
                ),
                'NDP_MURMEDIA_BIG_16_9' => array(
                    'expression' => 'Mur media grand 16/9',
                    'bo'=>1
                ),
                'NDP_MURMEDIA_SMALL_SQUARE' => array(
                    'expression' => 'Mur Media petit carre',
                    'bo'=>1
                ),
                'NDP_MURMEDIA_BIG_SQUARE' => array(
                    'expression' => 'Mur Media grand carre',
                    'bo'=>1
                ),
            ));

        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_KEEP_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_BO`, `MEDIA_FORMAT_FO`) VALUES
            ('NDP_MURMEDIA_SMALL_16_9', 1, 360, 640, 1.78, 1, 1),
            ('NDP_MURMEDIA_BIG_16_9', 1, 540, 960, 1.78, 1, 1),
            ('NDP_MURMEDIA_SMALL_SQUARE', 1, 640, 640, 1, 1, 1),
            ('NDP_MURMEDIA_BIG_SQUARE', 1, 960, 960, 1, 1, 1)
        ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_MURMEDIA_SMALL_16_9',
            'NDP_MURMEDIA_BIG_16_9',
            'NDP_MURMEDIA_SMALL_SQUARE',
            'NDP_MURMEDIA_BIG_SQUARE',
        ));
        $this->addSql('DELETE FROM psa_media_format WHERE MEDIA_FORMAT_LABEL IN("NDP_MURMEDIA_SMALL_16_9","NDP_MURMEDIA_BIG_16_9","NDP_MURMEDIA_SMALL_SQUARE","NDP_MURMEDIA_BIG_SQUARE") ');
    }
}
