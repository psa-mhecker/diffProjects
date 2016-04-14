<?php

namespace Application\Migrations;

use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160114171548 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_ERROR_WS_BO_FORMS_BO' => array(
                    'expression' => 'Le Webservice BO Forms ne répond pas',
                    'bo'=>1
                ),
                'NDP_ERROR_TEXT' => array(
                    'expression' => 'Text erreur',
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
                'NDP_ERROR_WS_BO_FORMS_BO',
                'NDP_ERROR_TEXT'
            )
        );
    }
}
