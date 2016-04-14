<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150721145925 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('WS_MOTEUR_CONFIG_SELECT', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_MOTEUR_CONFIG_CONFIG', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_MOTEUR_CONFIG_LOOK_COMBINATIONS', NULL, 2, NULL, NULL, 1, NULL),
            ('WS_MOTEUR_CONFIG_COMPARE_GRADE', NULL, 2, NULL, NULL, 1, NULL)
            ");



        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
           ('WS_MOTEUR_CONFIG_SELECT', 1, 1, 'Moteur de configuration (Select)'),
           ('WS_MOTEUR_CONFIG_CONFIG', 1, 1, 'Moteur de configuration (Config)'),
           ('WS_MOTEUR_CONFIG_LOOK_COMBINATIONS', 1, 1, 'Moteur de configuration (Look Combinations)'),
           ('WS_MOTEUR_CONFIG_COMPARE_GRADE', 1, 1, 'Moteur de configuration (Compare Grade)')
            ");

        $this->addSql("INSERT INTO psa_liste_webservices (ws_id, ws_name) VALUES
            (9, 'WS_MOTEUR_CONFIG_SELECT'),
            (10, 'WS_MOTEUR_CONFIG_CONFIG'),
            (11, 'WS_MOTEUR_CONFIG_LOOK_COMBINATIONS'),
            (12, 'WS_MOTEUR_CONFIG_COMPARE_GRADE')"
            );

        $this->addSql("DELETE FROM psa_liste_webservices where ws_name IN('WS_MOTEUR_CONFIG_PROD','WS_MOTEUR_CONFIG_PREVIEW')");
        $this->addSql("DELETE FROM psa_label where LABEL_ID IN('WS_MOTEUR_CONFIG_PROD','WS_MOTEUR_CONFIG_PREVIEW')");
        $this->addSql("DELETE FROM psa_label_langue_site where LABEL_ID IN('WS_MOTEUR_CONFIG_PROD','WS_MOTEUR_CONFIG_PREVIEW')");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID IN('WS_MOTEUR_CONFIG_SELECT','WS_MOTEUR_CONFIG_CONFIG','WS_MOTEUR_CONFIG_LOOK_COMBINATIONS','WS_MOTEUR_CONFIG_COMPARE_GRADE')");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID IN('WS_MOTEUR_CONFIG_SELECT','WS_MOTEUR_CONFIG_CONFIG','WS_MOTEUR_CONFIG_LOOK_COMBINATIONS','WS_MOTEUR_CONFIG_COMPARE_GRADE')");
        $this->addSql("DELETE FROM psa_liste_webservices WHERE ws_name IN('WS_MOTEUR_CONFIG_SELECT','WS_MOTEUR_CONFIG_CONFIG','WS_MOTEUR_CONFIG_LOOK_COMBINATIONS','WS_MOTEUR_CONFIG_COMPARE_GRADE')");

    }
}
