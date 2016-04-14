<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150727171128 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_liste_webservices SET ws_name='WS_GEST_RANGE_MANAGER' where ws_id=5");

        $this->addSql("INSERT INTO psa_liste_webservices (ws_id, ws_name) VALUES
            (14, 'WS_GEST_GAMME')");
        $this->addSql("UPDATE psa_liste_webservices set ws_url='https://tools-ndp-dev.peugeot.com/relay/relay.php?url=http://configurateur3d.peugeot.inetpsa.com/CFG3PSite/WsGamme.svc?WSDL' where ws_id=14");
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('WS_GEST_RANGE_MANAGER', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
           ('WS_GEST_RANGE_MANAGER', 1, 1, 'Webservice Range Manager')
            ");

        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling DROP FOREIGN KEY FK_23B42BB5EBE647F0');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5EBE647F0 FOREIGN KEY (MODEL_SILHOUETTE_ID) REFERENCES psa_ws_gdg_model_silhouette_site (ID) ON DELETE CASCADE');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID IN('WS_GEST_RANGE_MANAGER')");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID IN('WS_GEST_RANGE_MANAGER')");
        $this->addSql("DELETE FROM psa_liste_webservices WHERE ws_id = 14");
        $this->addSql("UPDATE psa_liste_webservices set ws_name='WS_GEST_GAMME' where ws_id = 5");


    }
}
