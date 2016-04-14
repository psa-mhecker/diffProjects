<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150709133851 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
             ("NDP_STANDARD", NULL, 2, NULL, NULL, 1, NULL),
             ("USED_COUNT", NULL, 2, NULL, NULL, 1, NULL),
             ("NDP_LIB_CTA_CLICK_TO_CHAT", NULL, 2, NULL, NULL, 1, NULL),
             ("NDP_LIB_CTA_POPIN", NULL, 2, NULL, NULL, 1, NULL),
             ("NDP_LIB_CTA_CLICK_TO_CALL", NULL, 2, NULL, NULL, 1, NULL),
             ("NDP_LIB_CTA_JS", NULL, 2, NULL, NULL, 1, NULL)
            '
        );
        $this->addSql(
            'INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
             ("NDP_STANDARD",1, 1,"Standard"),
             ("USED_COUNT",1, 1,"Nombre d\'utilisation"),
             ("NDP_LIB_CTA_CLICK_TO_CHAT", 1, 1,"ID Chat"),
             ("NDP_LIB_CTA_POPIN", 1, 1,"CTA Popin"),
             ("NDP_LIB_CTA_CLICK_TO_CALL", 1, 1,"Téléphone"),
             ("NDP_LIB_CTA_JS", 1, 1,"Onclick (JS)")
            '
        );
        $this->addSql("UPDATE psa_label_langue_site set LABEL_TRANSLATE='URL du CTA' WHERE LABEL_ID='NDP_URL_CTA'");
        $this->addSql("UPDATE psa_label_langue_site set LABEL_TRANSLATE='Popin' WHERE LABEL_ID = 'NDP_POPIN'");
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
                 "NDP_STANDARD",
                 "NDP_LIB_CTA_CLICK_TO_CHAT",
                 "NDP_LIB_CTA_CLICK_TO_CALL",
                 "NDP_LIB_CTA_POPIN",
                 "NDP_LIB_CTA_JS",
                 "USED_COUNT"
                )'
            );
        }

    }
}
