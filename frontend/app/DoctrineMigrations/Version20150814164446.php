<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150814164446 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Visuel 16/9 web et mobile' WHERE LABEL_ID ='VISUEL_16_9_WEB_MOBILE' AND SITE_ID = 1 AND LANGUE_ID = 1");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Visuel 16/9 web mobile' WHERE LABEL_ID ='VISUEL_16_9_WEB_MOBILE' AND SITE_ID = 1 AND LANGUE_ID = 1");
    }
}
