<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160311172441 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array(
                'FORM_MSG_NUMBER_POSITIVE' => array(
                    'expression' => ' un entier numérique positif à partir de 1 ',
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
        $this->downTranslations(array('FORM_MSG_NUMBER_POSITIVE'));
    }
}
