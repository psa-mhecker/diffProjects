<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150821135621 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
                "NDP_PC60_BY",
                "NDP_PC60_REEVOO",
                "NDP_PC60_READ_356_REVIEWS"
                )
        ');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
