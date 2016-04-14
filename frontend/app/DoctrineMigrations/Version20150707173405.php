<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150707173405 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_LCDV6", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_CODE_VU", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_CODE_VP", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_SILHOUETTE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MODEL", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_GENRE_LCDV6", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_CODE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_ADD_VIEW_ANGLE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_INITIAL_ANGLE", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_SILHOUETTE", 1, 1, "Silhouette"),
            ("NDP_CODE_VU", 1, 1, "VU"),
            ("NDP_CODE_VP", 1, 1, "VP"),
            ("NDP_LCDV6", 1, 1, "LCDV6"),
            ("NDP_MODEL", 1, 1, "Modèle"),
            ("NDP_MSG_GENRE_LCDV6", 1, 1, "Modèle silhouette "),
            ("NDP_CODE", 1, 1, "Code"),
            ("NDP_INITIAL_ANGLE", 1, 1, "Angle initial"),
            ("NDP_ADD_VIEW_ANGLE", 1, 1, "Ajouter : Angle de vue")'
        );

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
                "NDP_LCDV6", "NDP_SILHOUETTE", "NDP_MODEL", "NDP_MSG_GENRE_LCDV6", "NDP_CODE", "NDP_INITIAL_ANGLE", "NDP_ADD_VIEW_ANGLE", "NDP_CODE_VU", "NDP_CODE_VP"
                )');
        }
    }
}
