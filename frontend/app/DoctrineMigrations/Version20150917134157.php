<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150917134157 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site set LABEL_TRANSLATE='caractères au maximum pour le champ' where LABEL_ID='FORM_MSG_LIMIT_2' and LANGUE_ID=1 and SITE_ID=1");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site set LABEL_TRANSLATE='caractères au maximum pour' where LABEL_ID='FORM_MSG_LIMIT_2' and LANGUE_ID=1 and SITE_ID=1");
    }
}
