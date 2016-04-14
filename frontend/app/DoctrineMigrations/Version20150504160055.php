<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150504160055 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Colonne 1' WHERE LABEL_ID ='NDP_COLONNE1' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Colonne 2' WHERE LABEL_ID ='NDP_COLONNE2' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Colonne 3' WHERE LABEL_ID ='NDP_COLONNE3' AND SITE_ID = 1 AND LANGUE_ID = 1");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
