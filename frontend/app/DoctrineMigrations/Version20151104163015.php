<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

class Version20151104163015 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array('NDP_EQUALLY_BALANCED_TEXT'=>['expression'=>'pensez à équilibrer le texte des colonnes','bo'=>1]),
            array('NDP_CHARACTERS'=>['expression'=>'Caractères', 'bo'=>1])
        );

     }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->downTranslations(array('NDP_EQUALLY_BALANCED_TEXT', 'NDP_CHARACTERS'));
    }
}
