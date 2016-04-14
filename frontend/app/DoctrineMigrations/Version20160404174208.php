<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160404174208 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_TWITTER' => array(
                    'expression' => "Twitter",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_TWITTER_ID' => array(
                    'expression' => "Identifiant Twitter",
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
                'NDP_TWITTER',
                'NDP_TWITTER_ID',
            )
        );

    }
}
