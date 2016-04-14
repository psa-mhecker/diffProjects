<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150603121706 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ("NDP_TITLE_SLICE_DESKTOP", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TITLE_SLICE_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TOGGLE_OPEN_DEFAULT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PICTO_DESKTOP", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ("NDP_TITLE_SLICE_DESKTOP", 1, 1, "Titre tranche desktop"),
                ("NDP_TITLE_SLICE_MOBILE", 1, 1, "Titre tranche mobile"),
                ("NDP_TOGGLE_OPEN_DEFAULT", 1, 1, "Accordéon ouvert par défaut"),
                ("NDP_PICTO_DESKTOP", 1, 1, "Picto desktop")
                ');
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'URL CTA' WHERE LABEL_ID ='NDP_URL_CTA' AND SITE_ID = 1 AND LANGUE_ID = 1");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 (
                 "NDP_TITLE_SLICE_DESKTOP",
                 "NDP_TOGGLE_OPEN_DEFAULT",
                 "NDP_TITLE_SLICE_MOBILE",
                 "NDP_PICTO_DESKTOP"
                 )
                '
            );
        }
    }
}
