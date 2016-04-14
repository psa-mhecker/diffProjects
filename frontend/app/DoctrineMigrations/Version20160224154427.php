<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160224154427 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_ADD_RANGE_BAR' => array(
                    'expression' => "Ajouter modèle",
                    'bo' => 1
                ),
                'NDP_LABEL_FOR_MOBILE_ONLY' => array(
                    'expression' => "libellé spécial mobile",
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
            'NDP_ADD_RANGE_BAR',
            'NDP_LABEL_FOR_MOBILE_ONLY',
        ));
    }
}
