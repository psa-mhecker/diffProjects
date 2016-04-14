<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150707112224 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
             ("CTA_USED_ON", NULL, 2, NULL, NULL, 1, NULL),
             ("LIBELLE_BO", NULL, 2, NULL, NULL, 1, NULL),
             ("MEDIA_WEB", NULL, 2, NULL, NULL, 1, NULL),
             ("MEDIA_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
             ("SELF" ,  NULL, 2, NULL, NULL, 1, NULL),
             ("BLANK",  NULL, 2, NULL, NULL, 1, NULL ),
             ("CLICK_TO_CALL",  NULL, 2, NULL, NULL, 1, NULL),
             ("CLICK_TO_CHAT", NULL, 2, NULL, NULL, 1, NULL),
             ("JS", NULL, 2, NULL, NULL, 1, NULL),
             ("IN_PAGE",  NULL, 2, NULL, NULL, 1, NULL),
             ("IN_HOME", NULL, 2, NULL, NULL, 1, NULL)
            '
        );
        $this->addSql(
            'INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
             ("CTA_USED_ON",1, 1,"CTA utilise sur:"),
             ("LIBELLE_BO", 1, 1,"Libelle BO:"),
             ("MEDIA_WEB", 1, 1,"Vignette web: "),
             ("MEDIA_MOBILE", 1, 1,"Picto mobile:"),
             ("SELF", 1, 1,"Self"),
             ("BLANK", 1, 1,"Blank"),
             ("CLICK_TO_CALL", 1, 1,"Click to call"),
             ("CLICK_TO_CHAT", 1, 1,"Click to chat"),
             ("JS", 1, 1, "Code JS"),
             ("IN_PAGE", 1, 1,"dans la rubrique"),
             ("IN_HOME", 1, 1,"dans l\'acceuil")
            '
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {


        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (

                 "CTA_USED_ON",
                  "LIBELLE_BO",
                  "MEDIA_WEB",
                  "MEDIA_MOBILE",
                  "SELF", "BLANK",
                  "CLICK_TO_CALL",
                   "CLICK_TO_CHAT",
                   "JS",
                   "IN_PAGE",
                    "IN_HOME"

                )'
            );
        }
    }

}
