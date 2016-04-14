<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160310130630 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_MSG_DIFUSSION_NO_UNIQUE_TEMPLATE' => array(
                    'expression' => "Les pages de type gabarits uniques (Accueil, 404 etc) ne peuvent être diffusée",
                    'bo' => 1
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
            'NDP_MSG_DIFUSSION_NO_UNIQUE_TEMPLATE',
        ));
    }
}
