<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160112141756 extends  AbstractPsaMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array(
                'NDP_AGENT' => array(
                    'expression' => 'Agent',
                    'bo'=>0,
                    'fo'=>1
                ),
                'NDP_DEALER' => array(
                    'expression' => 'Concessionaire',
                    'bo'=>0,
                    'fo'=>1
                ),
                'NDP_OPENING_HOURS' => array(
                    'expression' => 'Horaires d\'ouverture',
                    'bo'=>0,
                    'fo'=>1
                ),
                'NDP_DEALER_SERVICES' => array(
                    'expression' => 'Services',
                    'bo'=>0,
                    'fo'=>1
                ),
                'NDP_PHONENUMBER' => array(
                    'expression' => 'Tel.',
                    'bo'=>0,
                    'fo'=>1,

                ),
                'NDP_BACK_TO_RESULTS' => array(
                    'expression' => 'Retour aux points de vente.',
                    'bo'=>0,
                    'fo'=>1
                )

            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->downTranslations(array(
            'NDP_AGENT',
            'NDP_DEALER',
            'NDP_OPENING_HOURS',
            'NDP_DEALER_SERVICES',
            'NDP_PHONENUMBER',
            'NDP_BACK_TO_RESULTS',
        ));

    }
}

