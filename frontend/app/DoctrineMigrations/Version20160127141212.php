<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160127141212 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `psa_ws_gdg_model_silhouette_site` ADD DISPLAY_PRICE TINYINT NOT NULL DEFAULT 1');
        $this->upTranslations(array(
            'NDP_MESSAGE_DISPLAY_PRICE' => array(
                'expression' => 'Le paramétrage de l\'affichage des prix véhicules concerne la Range Bar, le Car Picker, le Car Selector et la Présentation Showroom. Le paramétrage par modèle/regroupement de silhouettes surcharge le paramétrage transverse',
                'bo'=>1,
            ),
            'NDP_TRANSLATION_KEY_CASH_PRICE_LEGAL_MENTION' => array(
                'expression' => 'La clé de traduction des mentions légales est NDP_LEGAL_MENTION_CASH_PRICE',
                'bo'=>1,
            ),
            'NDP_MESSAGE_DISPLAY_PRICE_SITE' => array(
                'expression' => 'Le paramétrage de l\'affichage des prix véhicules concerne la Range Bar, le Car Picker, le Car Selector et la Présentation Showroom. Il peut être désactivé par modèle/regroupements de silhouettes',
                'bo'=>1,
            ),
            'NDP_LEGAL_MENTION_CASH_PRICE' => array(
                'expression' => 'Mentions légales lorem ipsum',
                'bo'=>0,
                'fo' => 1,
            ),
        ));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `psa_ws_gdg_model_silhouette_site` DROP COLUMN DISPLAY_PRICE');
        $this->downTranslations(array(
            'NDP_MESSAGE_DISPLAY_PRICE',
            'NDP_TRANSLATION_KEY_CASH_PRICE_LEGAL_MENTION',
            'NDP_MESSAGE_DISPLAY_PRICE_SITE',
            'NDP_LEGAL_MENTION_CASH_PRICE',
        ));
    }
}
