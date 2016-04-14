<?php
namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160104114409 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array(
                'NDP_2_TO_4_VISUALS' => array(
                    'expression' => 'De 2 à 4 visuels.',
                    'bo'=>1
                ),
                'NDP_MEDIA_16_9' => array(
                    'expression' => '16/9',
                    'bo'=>1
                ),
                'NDP_MEDIA_DIMENSION_THUMBNAIL' => array(
                    'expression' => 'Vignette dimension véhicule',
                    'bo'=>1
                ),
            ));

        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_KEEP_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_BO`, `MEDIA_FORMAT_FO`) VALUES
            ('NDP_MEDIA_16_9', 1, 720, 1280, 1.78, 1, 1),
            ('NDP_MEDIA_DIMENSION_THUMBNAIL', 1, 100, 200, 2, 1, 1)
        ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_2_TO_4_VISUALS',
            'NDP_MEDIA_16_9',
            'NDP_MEDIA_DIMENSION_THUMBNAIL',
        ));
        $this->addSql('DELETE FROM psa_media_format WHERE MEDIA_FORMAT_LABEL IN("NDP_MEDIA_16_9","NDP_MEDIA_DIMENSION_THUMBNAIL") ');
    }
}
