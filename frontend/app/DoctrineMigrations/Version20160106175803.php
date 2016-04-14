<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160106175803 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_APV_NO_APV' => array(
                    'expression' => 'Veuillez créer des prestations après vente',
                    'bo'=>1
                ),
                'NDP_APV' => array(
                    'expression' => 'APV a associé',
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
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(
            array('NDP_APV_NO_APV', 'NDP_APV')
        );

    }
}
