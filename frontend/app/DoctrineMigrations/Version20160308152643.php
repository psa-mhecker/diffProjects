<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160308152643 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->replaceTranslations(
            array(
                'NDP_CARPICKER_VEHICULES_INFO' => array(
                    'expression' => "L'ensemble des éléments des véhicules sont configurés dans le gestionnaire de gamme",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
                'NDP_CARPICKER_CTA_CONFIGURATOR' => array(
                    'expression' => "CTA pour le configurator",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );

        $this->upTranslations(
            array(
                'NDP_CARPICKER_CTA_CONFIGURATOR' => array(
                    'expression' => "CTA pour le configurator",
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
        $this->replaceTranslations(
            array(
                'NDP_CARPICKER_VEHICULES_INFO' => array(
                    'expression' => "Ajout maximum de 20 modèles/regroupements de silhouettes issus de la Gestion de la gamme. Seuls les 9 premiers seront affichés sur mobile.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'NDP_CARPICKER_VEHICULES_INFO' => array(
                    'expression' => "Add a maximum of 20 models/groups of car outlines coming from the Range management. Only the first 9 are displayed on mobile.",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
            )
        );

        $this->downTranslations(
            array(
                'NDP_CARPICKER_CTA_CONFIGURATOR'
            )
        );
    }
}
