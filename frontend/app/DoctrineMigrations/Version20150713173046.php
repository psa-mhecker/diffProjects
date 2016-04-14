<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150713173046 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MSG_ERROR_NO_SILHOUETTE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_SHOW_SILHOUETTE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_CHANGE_CAR_MOBILE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_REDIRECT_CAR_SELECTOR', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_MSG_ERROR_NO_SILHOUETTE', 1, 1, 'Veuillez renseigner le modèle/regroupement de silhouettes sur la Welcome Page du Showroom'),
            ('NDP_MSG_SHOW_SILHOUETTE', 1, 1, 'Affichage des regroupements de silhouettes de la'),
            ('NDP_MSG_CHANGE_CAR_MOBILE', 1, 1, 'Bouton Changer de véhicule (mobile)'),
            ('NDP_MSG_REDIRECT_CAR_SELECTOR', 1, 1, 'Recommandation : redirection sur le Car selector.')
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
                    "NDP_MSG_SHOW_SILHOUETTE", "NDP_MSG_ERROR_NO_SILHOUETTE", "NDP_MSG_REDIRECT_CAR_SELECTOR", "NDP_MSG_CHANGE_CAR_MOBILE"
                )'
            );
        }
    }
}
