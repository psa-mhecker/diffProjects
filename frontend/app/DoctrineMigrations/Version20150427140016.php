<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150427140016 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_CARPICKER_VEHICULES_INFO', NULL, 2, NULL, NULL, 1, NULL),
                ('MODELCAR', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MODELE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CUSTOM_LINK', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_YES', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_NO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SHOW_PRICE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CTA_CARPICKER', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_CARPICKER_VEHICULES_INFO', 1, 1, 'Ajout maximum de 20 véhicules issus du référentiel modèle dans la rubrique XXX du BO. Sur le mobile seul les 9 premiers véhicules seront affichés.'),
            ('MODELCAR', 1, 1, 'Modèle de véhicules'),
            ('NDP_MODELE', 1, 1, 'Modèle'),
            ('NDP_CUSTOM_LINK', 1, 1, 'Autre URL'),
            ('NDP_YES', 1, 1, 'Oui'),
            ('NDP_NO', 1, 1, 'Non'),
            ('NDP_SHOW_PRICE', 1, 1, 'Afficher les prix'),
            ('NDP_CTA_CARPICKER', 1, 1, 'CTA Car Picker')
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
             "NDP_CARPICKER_VEHICULES_INFO","MODELCAR","NDP_MODELE","NDP_CUSTOM_LINK",
             "NDP_YES", "NDP_NO", "NDP_SHOW_PRICE", "NDP_CTA_CARPICKER"
             )
        ');
        }
    }
}
