<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160322120135 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_PEUGEOT_FONT' => array(
                    'expression' => "La police Peugeot",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_USE_PEUGEOT_FONT' => array(
                    'expression' => "Activation de la police Peugeot",
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
                'NDP_PEUGEOT_FONT',
                'NDP_USE_PEUGEOT_FONT',
            )
        );
    }
}
