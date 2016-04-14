<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150805103035 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE = "Ajout maximum de 20 modèles/regroupements de silhouettes issus de la Gestion de la gamme. Seuls les 9 premiers seront affichés sur mobile." WHERE LABEL_ID ="NDP_CARPICKER_VEHICULES_INFO" AND SITE_ID = 1 AND LANGUE_ID = 1');
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_LIST_MODELCAR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ADD_LIST_MODELCAR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_LIST_MODELE', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_LIST_MODELCAR', 1, 1, 'Liste des véhicules'),
            ('NDP_ADD_LIST_MODELCAR', 1, 1, 'Ajouter un modèle/regroupement de silhouettes'),
            ('NDP_LIST_MODELE', 1, 1, 'Modèle/regroupement de silhouettes')
            ");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                "NDP_LIST_MODELCAR",
                "NDP_ADD_LIST_MODELCAR",
                "NDP_LIST_MODELE"
                )');
        }

    }
}
