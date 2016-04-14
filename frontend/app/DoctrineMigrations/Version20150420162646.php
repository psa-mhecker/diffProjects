<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150420162646 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_media_type (MEDIA_TYPE_ID, MEDIA_TYPE_LABEL) VALUES ('streamlike', 'Streamlike')");

        $this->addSql('ALTER TABLE psa_site ADD STREAMLIKE_CACHETIME INT NULL');

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('MEDIA_TYPE_STREAMLIKE', NULL, 2, NULL, NULL, 1, NULL),
                ('MEDIA_RATIO', NULL, 2, NULL, NULL, 1, NULL),
                ('MEDIA_CREDITS', NULL, 2, NULL, NULL, 1, NULL),
                ('DATE_UPDATE', NULL, 2, NULL, NULL, 1, NULL),
                ('MEDIA_VISIBILITY', NULL, 2, NULL, NULL, 1, NULL),
                ('STREAMLIKE_COMPANY_ID', NULL, 2, NULL, NULL, 1, NULL),
                ('STREAMLIKE_CACHETIME', NULL, 2, NULL, NULL, 1, NULL)
                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('MEDIA_TYPE_STREAMLIKE', 1, 1, 'Streamlike'),
            ('MEDIA_RATIO', 1, 1, 'Ratio'),
            ('MEDIA_CREDITS', 1, 1, 'Crédits'),
            ('DATE_UPDATE', 1, 1, 'Mise à jour'),
            ('MEDIA_VISIBILITY', 1, 1, 'visibilité'),
            ('STREAMLIKE_COMPANY_ID', 1, 1, 'Streamlike company_id.'),
            ('STREAMLIKE_CACHETIME', 1, 1, 'Streamlike Durée cache (en min)')
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
             "MEDIA_TYPE_STREAMLIKE","MEDIA_RATIO","MEDIA_CREDITS","DATE_UPDATE",
             "MEDIA_VISIBILITY","STREAMLIKE_COMPANY_ID","STREAMLIKE_CACHETIME"
             )
        ');
        }

    }
}
