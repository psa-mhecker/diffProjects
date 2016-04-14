<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150522110145 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'URL' WHERE LABEL_ID ='URL' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Vidéo' WHERE LABEL_ID ='NDP_VIDEO' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_GALLERY', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_TEMPLATE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_TEMPLATE_DESKTOP', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LINK', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_IMAGE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MEDIA', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_GALLERY', 1, 1, 'Galerie'),
                ('NDP_TEMPLATE', 1, 1, 'Template'),
                ('NDP_TEMPLATE_DESKTOP', 1, 1, 'Template desktop'),
                ('NDP_LINK', 1, 1, 'Lien'),
                ('NDP_IMAGE', 1, 1, 'Image'),
                ('NDP_MEDIA', 1, 1, 'Média')
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
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_GALLERY',
                 'NDP_TEMPLATE',
                 'NDP_TEMPLATE_DESKTOP',
                 'NDP_LINK',
                 'NDP_IMAGE',
                 'NDP_MEDIA'
                 )
                "
            );
        }
    }
}
