<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160308114457 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_ASK_CLEAR_CACHE' => array(
                    'expression' => "Êtes-vous sur de vouloir vider les caches ? ",
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
        $this->downTranslations(array(
            'NDP_ASK_CLEAR_CACHE',
        ));
    }
}
