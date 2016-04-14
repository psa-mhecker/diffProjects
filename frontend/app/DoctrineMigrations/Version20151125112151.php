<?php

namespace Application\Migrations;

use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125112151 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_CHARACTERS' => array(
                    'expression' => 'caractères',
                    'bo'=>1
                ),
                'NDP_NO_MEDIA' => array(
                    'expression' => 'Aucun media',
                    'bo'=>1
                ),
                'NDP_MAX' => array(
                    'expression' => 'maximum',
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
        $this->downTranslations(array('NDP_CHARACTERS', 'NDP_NO_MEDIA', 'NDP_MAX'));
    }
}
