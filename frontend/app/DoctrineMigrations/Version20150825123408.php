<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150825123408 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_label SET LABEL_ID = 'NDP_DECOUVRIR' WHERE LABEL_ID ='NDP_PF23_DECOUVRIR'");
        $this->addSql("UPDATE psa_label SET LABEL_ID = 'NDP_CONFIGURER' WHERE LABEL_ID ='NDP_PF23_CONFIGURER'");
        $this->addSql("UPDATE psa_label_langue SET LABEL_ID = 'NDP_DECOUVRIR' WHERE LABEL_ID ='NDP_PF23_DECOUVRIR'");
        $this->addSql("UPDATE psa_label_langue SET LABEL_ID = 'NDP_CONFIGURER' WHERE LABEL_ID ='NDP_PF23_CONFIGURER'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE psa_label SET LABEL_ID = 'NDP_PF23_DECOUVRIR' WHERE LABEL_ID ='NDP_DECOUVRIR'");
        $this->addSql("UPDATE psa_label SET LABEL_ID = 'NDP_PF23_CONFIGURER' WHERE LABEL_ID ='NDP_CONFIGURER'");
        $this->addSql("UPDATE psa_label_langue SET LABEL_ID = 'NDP_PF23_DECOUVRIR' WHERE LABEL_ID ='NDP_DECOUVRIR'");
        $this->addSql("UPDATE psa_label_langue SET LABEL_ID = 'NDP_PF23_CONFIGURER' WHERE LABEL_ID ='NDP_CONFIGURER'");

    }
}
