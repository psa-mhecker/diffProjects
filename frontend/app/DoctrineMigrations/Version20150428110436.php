<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150428110436 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_FORMAT_WEB', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DROIT', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_AVEC_VISUELS', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SANS_VISUEL', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_FORMAT_WEB', 1, 1, 'Format web'),
                ('NDP_DROIT', 1, 1, 'Droit'),
                ('NDP_SANS_VISUEL', 1, 1, 'Sans visuel'),
                ('NDP_AVEC_VISUELS', 1, 1, 'Avec visuel (bandeau)')
            ");
         $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Ajouter un toggle' WHERE LABEL_ID ='NDP_ADD_TOGGLE' AND SITE_ID = 1 AND LANGUE_ID = 1");
         $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Grand visuel mobile' WHERE LABEL_ID ='NDP_GRAND_VISUEL_MOBILE' AND SITE_ID = 1 AND LANGUE_ID = 1");

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
                "NDP_FORMAT_WEB",
                "NDP_DROIT", "NDP_SANS_VISUEL", "NDP_AVEC_VISUELS"
             )
        ');
        }

    }
}
