<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160120114550 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_FO_MY_PEUGEOT' => array(
                    'expression' => 'MY PEUGEOT',
                    'bo'=>0,
                    'fo'=>1
                ),
                'NDP_ERROR_FO_FORM_MESSAGE' => array(
                    'expression' => 'Nous avons rencontré une erreur avec les formulaires,il est indisponible pour le moment, nous vous invitons a revenir plus tard ou à contacter un point de vente',
                    'bo'=>0,
                    'fo'=>1
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
            'NDP_FO_MY_PEUGEOT',
            'NDP_ERROR_FO_FORM_MESSAGE',
        ));
    }
}
