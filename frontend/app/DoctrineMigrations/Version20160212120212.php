<?php

namespace Application\Migrations;


use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160212120212 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->upTranslations(
            array(
                'NDP_RECHERCHE_DIMENSION' => array(
                    'expression' => 'Dimension',
                    'bo' => 1,
                ),
                'DESKTOP_AND_MOBILE' => array(
                    'expression' => 'Desktop et mobile',
                    'bo' => 1,
                ),
                'NPD_CANT_CROP_IMAGE' => array(
                    'expression' => 'Le visuel sélectionné ne peut être croppé',
                    'bo' => 1,
                ),
            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_RECHERCHE_DIMENSION',
            'DESKTOP_AND_MOBILE',
            'NPD_CANT_CROP_IMAGE',
        ));
    }
}
