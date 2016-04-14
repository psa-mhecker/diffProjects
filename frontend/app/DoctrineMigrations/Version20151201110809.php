<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151201110809 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_BOOKLET_LINK' => array(
                    'expression' => 'Libellé du lien',
                    'bo'=>1
                ),
                'NDP_BOOKLET_PDF' => array(
                    'expression' => 'Lien du PDF',
                    'bo'=>1
                ),
                'NDP_ERROR_FILE_UPLOAD_FORMAT' => array(
                    'expression' => 'Le format du fichier que vous souhaitez uploader est non accepté',
                    'bo'=>1
                ),
                'NDP_ONLY_FILE_WITH_FORMAT' => array(
                    'expression' => 'Seuls les fichiers au format ',
                    'bo'=>1
                ),
                'NDP_ARE_ACCEPTED' => array(
                    'expression' => ' sont acceptés',
                    'bo'=>1
                ),
            )
        );

        $this->replaceTranslations(
            array(
                'FORMAT_ATTENDU_UPLOAD' => array(
                    'expression' => 'Format attendu à l\'upload : ',
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
        $this->downTranslations(array(
            'NDP_BOOKLET_LINK',
            'NDP_BOOKLET_PDF',
            'NDP_ERROR_FILE_UPLOAD_FORMAT',
            'NDP_ONLY_FILE_WITH_FORMAT',
            'NDP_ARE_ACCEPTED'
        ));
    }
}
