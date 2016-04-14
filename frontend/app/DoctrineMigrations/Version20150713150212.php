<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150713150212 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
         $this->addSql("UPDATE psa_label_langue_site 
                        SET LABEL_TRANSLATE = 'Votre titre de rubrique est trop long, il est conseillé de ne pas dépasser les 50 caractères pour un titre.' 
                        WHERE psa_label_langue_site.LABEL_ID = 'ALERT_PAGE_TITLE_LONG_MAX' 
                        AND psa_label_langue_site.LANGUE_ID =1;
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
         $this->addSql("UPDATE psa_label_langue_site 
                        SET LABEL_TRANSLATE = 'Votre titre de rubrique est trop long, il est conseillé de ne pas dépasser les 70 caractères pour un titre.' 
                        WHERE psa_label_langue_site.LABEL_ID = 'ALERT_PAGE_TITLE_LONG_MAX' 
                        AND psa_label_langue_site.LANGUE_ID =1;
            ");
    }
}
