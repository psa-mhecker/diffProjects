<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150428162821 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_MEDIA_SHOWROOM', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MEDIA_SHOWROOM_INFO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_VERSION_WEB', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_COLUMN_1_4', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_COLUMN_3_4', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MODELCAR', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ANCRE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_VERSION_MOBILE', NULL, 2, NULL, NULL, 1, NULL)
                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_MEDIA_SHOWROOM', 1, 1, 'Média des pages du showroom'),
                ('NDP_VERSION_MOBILE', 1, 1, 'Version mobile'),
                ('NDP_VERSION_WEB', 1, 1, 'Version web'),
                ('NDP_COLUMN_1_4', 1, 1, 'Colonne 1/4'),
                ('NDP_COLUMN_3_4', 1, 1, 'Colonne 3/4'),
                ('NDP_ANCRE', 1, 1, 'Ancres (Cette tranche est à remplir en fin de construction de page) '),
                ('NDP_MODELCAR', 1, 1, 'Modèle de véhicules'),
                ('NDP_MEDIA_SHOWROOM_INFO', 1, 1,  'Veuillez cocher les média que vous souhaitez afficher dans le mur média')          
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
             "NDP_MEDIA_SHOWROOM","NDP_MEDIA_SHOWROOM_INFO", "NDP_VERSION_WEB", "NDP_VERSION_MOBILE",
             "NDP_COLUMN_1_4", "NDP_COLUMN_3_4", "NDP_ANCRE"
             )
        ');
        }

    }
}
