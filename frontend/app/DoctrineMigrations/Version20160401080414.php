<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160401080414 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_INVALID_URLS' => array(
                    'expression' => "Listes des urls invalides dans le fichiers",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_EXISTING_URLS' => array(
                    'expression' => "Listes des redirections déjà existantes",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_REDIRECT_NOT_FOUND' => array(
                    'expression' => "L'url de destination n'as pas été trouvée sur le site",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_REDIRECT_CHOOSE_FILE_WARNING' => array(
                    'expression' => "Merci de sélectionner un fichier à importer",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_IMPORT_REDIRECT_SUCCESS' => array(
                    'expression' => "L'import des urls a réussi",
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
                'NDP_INVALID_URLS',
                'NDP_EXISTING_URLS',
                'NDP_REDIRECT_NOT_FOUND',
                'NDP_REDIRECT_CHOOSE_FILE_WARNING',
                'NDP_IMPORT_REDIRECT_SUCCESS',

            )
        );

    }
}
