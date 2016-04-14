<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150709094353 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        //constante BO
        $this->addSql(
            'INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
             ("NDP_ZONE_PUSH_MOBILE", NULL, 2, NULL, NULL, 1, NULL),
             ("NDP_MSG_PUSH_MOBILE_DISPLAY_CONDITION", NULL, 2, NULL, NULL, 1, NULL),
             ("NDP_APP_HEADER", NULL, 2, NULL, NULL, 1, NULL)
            '
        );
        $this->addSql(
            'INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
             ("NDP_ZONE_PUSH_MOBILE",1, 1,"Zone push mobile"),
             ("NDP_MSG_PUSH_MOBILE_DISPLAY_CONDITION", 1, 1,"La zone de push mobile s’affiche uniquement sur le desktop web."),
             ("NDP_APP_HEADER", 1, 1,"Bandeau Application")
            '
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
                 "NDP_ZONE_PUSH_MOBILE",
                 "NDP_MSG_PUSH_MOBILE_DISPLAY_CONDITION",
                 "NDP_APP_HEADER"
                )'
            );
        }
    }
}
