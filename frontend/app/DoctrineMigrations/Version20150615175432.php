<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150615175432 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
        ("NDP_DISPLAY_BACK_TO_MOBILE_BUTTON", NULL, 2, NULL, NULL, 1, NULL),
        ("NDP_MODEL_GRP_SILH", NULL, 2, NULL, NULL, 1, NULL),
        ("IN_SITE_MAP", NULL, 2, NULL, NULL, 1, NULL),
        ("IN_SITE_MENU", NULL, 2, NULL, NULL, 1, NULL)
        ');

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_DISPLAY_BACK_TO_MOBILE_BUTTON', 1, 1, 'Activer le bouton retour sur le Mobile'),
            ('NDP_MODEL_GRP_SILH', 1, 1, 'Modèle / Regroupement de silhouettes' ),
            ('IN_SITE_MAP', 1, 1, 'dans la navigation' ),
            ('IN_SITE_MENU',  1, 1, 'dans le plan du site')");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
              'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                 "NDP_DISPLAY_BACK_TO_MOBILE_BUTTON",
                 "IN_SITE_MAP",
                 "IN_SITE_MENU",
                 "NDP_MODEL_GRP_SILH"
                 )
                '
            );
        }
    }
}
