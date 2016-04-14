<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150722150513 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('WS_BO_FORMS', NULL, 2, NULL, NULL, 1, NULL)
            ");



        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
           ('WS_BO_FORMS', 1, 1, 'Bo Forms')
            ");

        $this->addSql("INSERT INTO psa_liste_webservices (ws_id, ws_name) VALUES
            (15, 'WS_BO_FORMS')"
        );


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID = 'WS_BO_FORMS' ");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID = 'WS_BO_FORMS'");
        $this->addSql("DELETE FROM psa_liste_webservices WHERE ws_name = 'WS_BO_FORMS'");

    }
}
