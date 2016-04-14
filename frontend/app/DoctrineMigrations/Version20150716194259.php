<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150716194259 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="Rechercher sur le site" WHERE LABEL_ID="NDP_SEARCH_ON_SITE"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="CEPENDANT VOUS POUVEZ :" WHERE LABEL_ID="NDP_HOWEVER_YOU_CAN"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="ERREUR 404" WHERE LABEL_ID="NDP_ERROR_404"');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
