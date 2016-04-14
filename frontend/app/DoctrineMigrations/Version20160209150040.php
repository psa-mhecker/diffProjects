<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160209150040 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'MAX_SIZE_IMAGE' => array(
                    'expression' => 'Le fichier est trop voluminueux. Voici les poids max: images JPEG, JPG, PNG, GIF : *10Mo*.',
                    'bo' => 1,
                ),
                'MAX_SIZE_FILE' => array(
                    'expression' => 'Le fichier est trop voluminueux. Voici les poids max: brochures PDF, DOC, DOCX, XLS, XLSX: *16Mo*.',
                    'bo' => 1,
                ),
                'MEDIA_FILE_CAUSES_UPLOAD_ERROR' => array(
                    'expression' => 'Le fichier suivant a empêché le téléchargement de un ou plusieurs fichiers',
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
        $this->downTranslations(
            array(
                'MAX_SIZE_IMAGE',
                'MAX_SIZE_FILE',
                'MEDIA_FILE_CAUSES_UPLOAD_ERROR',
            )
        );
    }
}
