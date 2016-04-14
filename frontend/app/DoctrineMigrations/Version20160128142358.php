<?php
namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160128142358 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_MSG_ALERT_VIDEO' => array(
                    'expression' => 'les videos ne peuvent etre placé que dans les structures larges',
                    'bo'=>1,
                ),
                'NDP_MSG_ALERT_FORMAT' => array(
                    'expression' => 'L\'image est trop petite pour cet emplacement min({width}x{height})',
                    'bo'=>1,
                ),
                'NDP_LIST_STRUCTURE' => array(
                    'expression' => 'Liste des structures.',
                    'bo'=>1,
                ),
                'NDP_MSG_ADD_STRUCTURE_DRAG_DROP' => array(
                    'expression' => 'Afin d\'ajouter une structure dans le Mur média, utilisez le drag & drop.',
                    'bo'=>1,
                ),
                'NDP_MEDIA_WALL' => array(
                    'expression' => 'Mur média',
                    'bo'=>1,
                ),
                'NDP_IMAGES_AND_VIDEOS' => array(
                    'expression' => 'Images et vidéos',
                    'bo'=>1,
                ),
                'NDP_MSG_SELECT_SHOWROOM' => array(
                    'expression' => 'Veuillez choisir un showroom afin d\'initialiser le mur média.',
                    'bo'=>1,
                ),
                'NDP_IMAGES_ONLY' => array(
                    'expression' => 'Images uniquement',
                    'bo'=>1,
                ),
                'NDP_DISPONIBLES' => array(
                    'expression' => 'Disponibles',
                    'bo'=>1,
                ),

            )
        );
        $this->replaceTranslations(array(
            'NDP_WIDESCREEN'=>array('expression'=>'Large (16/9)'),
        ));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_MSG_ALERT_VIDEO',
            'NDP_LIST_STRUCTURE',
            'NDP_MSG_ADD_STRUCTURE_DRAG_DROP',
            'NDP_MEDIA_WALL',
            'NDP_IMAGES_AND_VIDEOS',
            'NDP_MSG_SELECT_SHOWROOM',
            'NDP_IMAGES_ONLY',
            'NDP_DISPONIBLES',
            'NDP_MSG_ALERT_FORMAT',
        ));
    }
}
