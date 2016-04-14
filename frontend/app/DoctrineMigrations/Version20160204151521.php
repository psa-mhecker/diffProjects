<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160204151521 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_MSG_IFRAME_HEIGHT',
        ));

        $this->upTranslations(
            array(
                'NDP_MSG_IFRAME_DESKTOP_HEIGHT' => array(
                    'expression' => 'Hauteur par défaut : 1000px',
                    'bo' => 1,
                ),
                'NDP_MSG_IFRAME_MOBILE_HEIGHT' => array(
                    'expression' => 'Hauteur par défaut : 600px',
                    'bo' => 1,
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
            'NDP_MSG_IFRAME_DESKTOP_HEIGHT',
            'NDP_MSG_IFRAME_MOBILE_HEIGHT',
        ));
    }
}
