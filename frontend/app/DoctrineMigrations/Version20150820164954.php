<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150820164954 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addsql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_1_3', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addsql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_1_3', 1, 1, '1/3')
                ");
        $this->addsql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_2_3', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addsql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_2_3', 1, 1, '2/3')
                ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID ='NDP_1_3'");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID ='NDP_1_3'");
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID ='NDP_2_3'");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID ='NDP_2_3'");


    }
}
