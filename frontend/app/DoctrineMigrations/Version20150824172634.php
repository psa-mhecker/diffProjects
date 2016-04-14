<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150824172634 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('REPLACE INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("ALERT_PAGE_TITLE_LONG_MAX", NULL, 2, NULL, NULL, 1, NULL),
                ("FORM_MSG_LIMIT_2", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('REPLACE INTO  `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("ALERT_PAGE_TITLE_LONG_MAX", 1, 1, "Le titre de la page est trop long, il est conseillé de ne pas dépasser ##parammax_length## caractères pour un titre long. "),
                ("FORM_MSG_LIMIT_2", 1, 1, "caractères au maximum pour")


        ');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
