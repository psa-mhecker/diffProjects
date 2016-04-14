<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160309114815 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->replaceTranslations(array(
            'NDP_FOND_ET_DESCRIPTIF' => array(
                'expression' => 'Couleur de fond de la description',
                'bo' => 1
            ),
        ));
        $this->downTranslations(array('NDP_COULEUR_DESCRIPTION'));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->replaceTranslations(array(
            'NDP_FOND_ET_DESCRIPTIF' => array(
                'expression' => "Couleur de fond de l'entête et descriptif",
                'bo' => 1
            ),
        ));

        $this->upTranslations(array(
            'NDP_COULEUR_DESCRIPTION' => array(
                'expression' => 'Couleur de la description',
                'bo' => 1
            ),
        ));
    }
}
