<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160325083854 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_MIG_ERROR_ALL_IMAGE_REQUIRED' => array(
                    'expression' => "Vous devez faire un choix pour toutes les images.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_MIGRATE_IMAGE' => array(
                    'expression' => "Prochaine étape, recherche des images haute définition.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_MIGRATE_IMAGE_TITLE' => array(
                    'expression' => "Migration des images haute définition",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_MIGRATE_IMAGE_DESCRIPTION' => array(
                    'expression' => "Pour chacunes des images haute définition à droite choisissez si elle peut remplacer l'image basse definition de gauche. ",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ), 'NDP_MIG_IMAGE_SD' => array(
                    'expression' => "Image SD",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ), 'NDP_MIG_IMAGE_HD' => array(
                    'expression' => "Image HD",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ), 'NDP_MIG_IMAGE_SIMILARITE' => array(
                    'expression' => "Similarité",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ), 'NDP_MIG_IMAGE_REPLACE' => array(
                    'expression' => "Remplacer les images",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ), 'NDP_MIG_NO_IMAGE_FOUND' => array(
                    'expression' => "Aucune image HD de remplacement trouvée ",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                  'NDP_MIG_BACK_LIST' => array(
                    'expression' => "Retour a la migration de données",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_IMAGE_REPLACEMENT_SUCCESS' => array(
                    'expression' => "Les images ont bien été remplacées, la migration est un succès",
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
                'NDP_MIG_ERROR_ALL_IMAGE_REQUIRED',
                'NDP_MIG_MIGRATE_IMAGE',
                'NDP_MIG_MIGRATE_IMAGE_TITLE',
                'NDP_MIG_MIGRATE_IMAGE_DESCRIPTION',
                'NDP_MIG_IMAGE_SD',
                'NDP_MIG_IMAGE_HD',
                'NDP_MIG_IMAGE_SIMILARITE',
                'NDP_MIG_IMAGE_REPLACE',
                'NDP_MIG_NO_IMAGE_FOUND',
                'NDP_MIG_BACK_LIST',
                'NDP_MIG_IMAGE_REPLACEMENT_SUCCESS',
            )
        );
    }
}
