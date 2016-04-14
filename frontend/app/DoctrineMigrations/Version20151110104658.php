<?php

namespace Application\Migrations;


use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

class Version20151110104658 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array(
                'NDP_ERROR_ALL_VISUALS_SHOULD_HAVE_SAME_SIZE' => array(
                    'expression' => 'les images doivent avoir la meme taille',
                    'bo'=>1
                )
            )
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->downTranslations(array('NDP_ERROR_ALL_VISUALS_SHOULD_HAVE_SAME_SIZE'));


    }
}
