<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150615143643 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Interface du site' WHERE LABEL_ID ='NDP_REF_SITE' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Interface du site' WHERE LABEL_ID ='NDP_REF_SITE' AND SITE_ID = 2 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Site en maintenance (503)' WHERE LABEL_ID ='SITE_MAINTENANCE' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Site en maintenance (503)' WHERE LABEL_ID ='SITE_MAINTENANCE' AND SITE_ID = 2 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'URL du site en maintenance (503)' WHERE LABEL_ID ='URL_MAINTENANCE' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'URL du site en maintenance (503)' WHERE LABEL_ID ='URL_MAINTENANCE' AND SITE_ID = 2 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Il est nécessaire d\'activer le Webservice Financement BPF dans la rubrique Configuration des webservices afin d\'afficher les prix mensualisés' WHERE LABEL_ID ='NDP_MSG_WS_SFG_DISABLED' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Il est nécessaire d\'activer le Webservice Financement BPF dans la rubrique Configuration des webservices afin d\'afficher les prix mensualisés' WHERE LABEL_ID ='NDP_MSG_WS_SFG_DISABLED' AND SITE_ID = 2 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'À appliquer si la longueur provient du SI.' WHERE LABEL_ID ='NDP_DIMENSION_MULTIPLIER' AND SITE_ID = 2 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'À appliquer si la longueur provient du SI.' WHERE LABEL_ID ='NDP_DIMENSION_MULTIPLIER' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'À appliquer si le volume provient du SI.' WHERE LABEL_ID ='NDP_VOLUME_MULTIPLIER' AND SITE_ID = 2 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'À appliquer si le volume provient du SI.' WHERE LABEL_ID ='NDP_VOLUME_MULTIPLIER' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'À appliquer si la charge utile provient du SI.' WHERE LABEL_ID ='NDP_PAYLOAD_MULTIPLIER' AND SITE_ID = 2 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'À appliquer si la charge utile provient du SI.' WHERE LABEL_ID ='NDP_PAYLOAD_MULTIPLIER' AND SITE_ID = 1 AND LANGUE_ID = 1");

        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_MULTIPLIER", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_MULTIPLIER", 1, 1, "Multiplicateur"),
            ("NDP_MULTIPLIER", 1, 2, "Multiplicateur")'
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
                    "NDP_MULTIPLIER"
                )'
            );
        }
    }
}
