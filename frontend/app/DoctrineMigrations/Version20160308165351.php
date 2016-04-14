<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160308165351 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NO_PROPRIETAIRE' => array(
                    'expression' => "Vous n'êtes pas le propriétaire de l'image, vous ne pouvez la modifier",
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
            'NO_PROPRIETAIRE',
        ));
    }
}
