<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150618161021 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // renommage de NDP_PF44_PROBLEME_DE_CHARGEMENT en NDP_PROBLEME_DE_CHARGEMENT_AJAX pour utilisation dans d'autres tranches
        $tables = array('psa_label','psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql('UPDATE `'.$table.'` SET `LABEL_ID` = "NDP_PROBLEME_DE_CHARGEMENT_AJAX" WHERE `LABEL_ID` = "NDP_PF44_PROBLEME_DE_CHARGEMENT"');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

    }
}
