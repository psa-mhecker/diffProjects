<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150813174621 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //  modification messages d'erreur du titre long :

        $this->addsql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('ALERT_PAGE_TITLE_LONG_MAX', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addsql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('ALERT_PAGE_TITLE_LONG_MAX', 1, 1, 'Le titre de la page est trop long,il est conseillé de ne pas dépasser ##parammax_length## caractères pour un titre long')
                ");

        $this->addsql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('ALERT_CNT_TITLE_LONG_MAX', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addsql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('ALERT_CNT_TITLE_LONG_MAX', 1, 1, 'Le titre du contenu est trop long,il est conseillé de ne pas dépasser ##parammax_length## caractères pour un titre long')
                ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID ='ALERT_CNT_TITLE_LONG_MAX'");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID ='ALERT_CNT_TITLE_LONG_MAX'");
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID ='ALERT_PAGE_TITLE_LONG_MAX'");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID ='ALERT_PAGE_TITLE_LONG_MAX'");

    }
}

