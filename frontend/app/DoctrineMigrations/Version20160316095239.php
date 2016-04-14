<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160316095239 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_MSG_DIFFUSION_SUCCESS' => array(
                    'expression' => "La diffusion s'est terminée avec succès ",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MSG_DIFFUSION_ERROR' => array(
                    'expression' => "Une erreur s'est produite pendant la diffusion",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
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
        $this->downTranslations(
            array(
                'NDP_MSG_DIFFUSION_ERROR',
                'NDP_MSG_DIFFUSION_SUCCESS',
            )
        );

    }
}
