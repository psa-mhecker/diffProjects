<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151123151521 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_LABEL_CTA_BO' => array(
                    'expression' => 'Libellé CTA BO',
                    'bo'=>1
                ),
                'NDP_LABEL_CTA_FO' => array(
                    'expression' => 'Libellé CTA FO',
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
        $this->downTranslations(array('NDP_LABEL_CTA_BO', 'NDP_LABEL_CTA_FO'));
    }
}
