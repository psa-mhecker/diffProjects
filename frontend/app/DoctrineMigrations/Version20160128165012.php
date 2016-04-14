<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160128165012 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array(
                'NDP_GENERIC_4_3_640' => array(
                    'expression' => '4/3 Mobile Generic',
                    'bo' => 1,
                ),
                'NDP_DEFAULT_FORMAT' => array(
                    'expression' => 'format par defaut fo',
                    'bo' => 1,
                ),
                'NDP_PF2_DESKTOP' => array(
                    'expression' => 'Presentation showroom desktop',
                    'bo' => 1,
                ),
            ));

        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_KEEP_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_BO`, `MEDIA_FORMAT_FO`) VALUES
            ('NDP_DEFAULT_FORMAT', 1, 1080, 1920, 1.77, 0, 1),
            ('NDP_PF2_DESKTOP', 1, 600, 1280, 2.13, 1, 1),
            ('NDP_GENERIC_4_3_640', 1, 480, 640, 1.33, 1, 1)
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_DEFAULT_FORMAT',
            'NDP_PF2_DESKTOP',
            'NDP_GENERIC_4_3_640',
        ));

        $this->addSql('DELETE FROM psa_media_format WHERE MEDIA_FORMAT_LABEL IN("NDP_GENERIC_4_3_640","NDP_DEFAULT_FORMAT","NDP_PF2_DESKTOP") ');
    }
}
