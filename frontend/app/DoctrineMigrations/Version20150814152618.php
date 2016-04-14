<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150814152618 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("INSERT INTO psa_liste_webservices (ws_id, ws_name, ws_url) VALUES
            (16, 'WS_EDEALER','https://tools-ndp-dev.peugeot.com/relay/relay.php?url=http://dealers.fr.peugeot.inetpsa.com/Services/offerservice.asmx')");
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('WS_EDEALER', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
           ('WS_EDEALER', 1, 1, 'Webservice E-Dealer')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID IN('WS_EDEALER')");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID IN('WS_EDEALER')");
        $this->addSql("DELETE FROM psa_liste_webservices WHERE ws_id = 16");


    }
}
