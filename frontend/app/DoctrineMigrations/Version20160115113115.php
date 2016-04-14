<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160115113115 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_SHOW_TITLE' => array(
                    'expression' => 'Afficher le titre',
                    'bo'=>1
                ),
                'NDP_TITLE_MANUAL' => array(
                    'expression' => 'Titre manuel',
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
            array(
                'NDP_SHOW_TITLE',
                'NDP_TITLE_MANUAL'
            )
        );
    }
}
