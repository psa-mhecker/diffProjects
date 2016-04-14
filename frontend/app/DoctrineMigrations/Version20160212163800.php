<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160212163800 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'PAGE_URL_CANONIQUE_INFO' => array(
                    'expression' => 'Saisissez l\'url de la page qui sera la référente. A savoir : Une URL canonique correspond à la version référente d\'un ensemble de pages au contenu similaire pour éviter les cas de contenus dupliqués (duplicate content).',
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
            'PAGE_URL_CANONIQUE_INFO',
        ));
    }
}
