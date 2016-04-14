<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160315170043 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->downTranslations(
            array(
                'NDP_CARPICKER_CTA_CONFIGURATOR',
            )
        );

        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_ADD_MODEL' => array(
                    'expression' => "Ajouter modèle",
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
                'NDP_ADD_MODEL',
            )
        );
        
        $this->upTranslations(
            array(
                'NDP_CARPICKER_CTA_CONFIGURATOR' => array(
                    'expression' => "CTA pour le configurator",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
    }
}
