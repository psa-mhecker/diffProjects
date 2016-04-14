<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160119164501 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array(
                'NDP_PF11_CALL_US' => array(
                    'expression' => 'nous appeler',
                    'bo'=>0,
                    'fo'=>1
                ),
                'NDP_PF11_EMAIL_US' => array(
                    'expression' => 'nous écrire',
                    'bo'=>0,
                    'fo'=>1
                ),
            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->downTranslations(array(
            'NDP_PF11_CALL_US',
            'NDP_PF11_EMAIL_US',
        ));

    }
}
