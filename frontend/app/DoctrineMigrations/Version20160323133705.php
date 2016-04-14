<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160323133705 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_MIG_EXISTING_PAGE_ERROR' => array(
                    'expression' => "la page url ##paramUrl## existe déjà dans la langue ##paramLangueCode## (id:##paramLangueId##), dans la page ##paramPageTitle## (id:##paramPageId##) pour la version ##paramPageVersion##. la migration a échoué. Vous devez supprimer la page de la liste des pages ou la supprimer de la corbeille.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_XML_FILE_DOWNLOAD_SUCCESS' => array(
                    'expression' => "XML file successfully downloaded: ##paramXmlUrl##.",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
                'NDP_MIG_XML_FILE_DOWNLOADED_NOT_VALID' => array(
                    'expression' => "File downloaded doesn't seem to be a valid XML file. Make sure you selected the correct type of showroom and provided a valid showroom url.",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
                'NDP_MIG_ERROR_DOWNLOADING_XML_FILE' => array(
                    'expression' => "Error while trying to download. Make sure you selected the correct type of showroom and provided a valid showroom url.",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
                'NDP_MIG_ERROR_HOMEPAGE_NODE' => array(
                    'expression' => "No XML node '01_hompepage' found for the XML : ##paramXmlUrl##. Import could not start.",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
                'NDP_MIG_ERROR_MAINTOPIC_NODE' => array(
                    'expression' => "No XML node '02_main_topics' found for the XML : ##paramXmlUrl##. No sub pages was created.",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                )
            )
        );

        $this->replaceTranslations(
            array(
                'NDP_MIG_ERROR_DOWNLOADING_XML_FILE' => array(
                    'expression' => "Erreur lors du téléchargement. Assurez-vous que vous avez sélectionné le bon showroom et fourni une URL de showroom valide.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_XML_FILE_DOWNLOAD_SUCCESS' => array(
                    'expression' => "Fichier XML téléchargé avec succès: ##paramXmlUrl##.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_XML_FILE_DOWNLOADED_NOT_VALID' => array(
                    'expression' => "Le fichier XML uploadé semble invalide. Assurez-vous que vous avez sélectionné le bon showroom et fourni une URL de showroom valide.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_EXISTING_PAGE_ERROR' => array(
                    'expression' => "The url page ##paramUrl## already exist for language ##paramLangueCode## (id:##paramLangueId##), in page ##paramPageTitle## (id:##paramPageId##) for version ##paramPageVersion##. Migration cannot start. You have to remove the page from the pages' list or remove it from the trash.",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
                'NDP_MIG_ERROR_HOMEPAGE_NODE' => array(
                    'expression' => "Le node '01_hompepage' n'existe pas dans le XML: ##paramXmlUrl##. l'import a échoué.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_MIG_ERROR_MAINTOPIC_NODE' => array(
                    'expression' => "Le node '02_main_topics' n'existe pas dans le XML: ##paramXmlUrl##. Aucune sous pages créée.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
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
        $this->downTranslations(
            array(
                'NDP_MIG_EXISTING_PAGE_ERROR',
                'NDP_MIG_XML_FILE_DOWNLOAD_SUCCESS',
                'NDP_MIG_XML_FILE_DOWNLOADED_NOT_VALID',
                'NDP_MIG_ERROR_DOWNLOADING_XML_FILE',
                'NDP_MIG_ERROR_HOMEPAGE_NODE',
                'NDP_MIG_ERROR_MAINTOPIC_NODE',
            )
        );
    }
}
