<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160229115717 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'DIFFUSION_SELECT_MINIMUM_ONE_PAGE' => array(
                    'expression' => "Vous devez sélectionner au moin une page source !",
                    'bo' => 1
                ),
                'ADD_PAYS_CIBLES' => array(
                    'expression' => "Ajouter pays cible",
                    'bo' => 1
                ),
                'DIFFUSION_PAYS_CIBLE' => array(
                    'expression' => "Pays cible",
                    'bo' => 1
                ),
                'SITEADD' => array(
                    'expression' => "Site cible",
                    'bo' => 1
                ),
                'TEXTE_NOTIFICATION' => array(
                    'expression' => "Texte de notification",
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
            'DIFFUSION_SELECT_MINIMUM_ONE_PAGE',
            'ADD_PAYS_CIBLES',
            'DIFFUSION_PAYS_CIBLE',
            'SITEADD',
            'TEXTE_NOTIFICATION',
        ));
    }
}
