<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160118142138 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_MSG_ERROR_NO_MEDIAS_SHOWROOM' => array(
                    'expression' => 'Il n\'y a pas de médias dans ce showroom',
                    'bo'=>1
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
                'NDP_MSG_ERROR_NO_MEDIAS_SHOWROOM',
            )
        );
    }
}
