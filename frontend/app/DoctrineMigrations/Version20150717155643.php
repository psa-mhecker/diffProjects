<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150717155643 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            "UPDATE psa_area SET AREA_FOOT = '' WHERE AREA_ID = 10"
        );
        
        $this->addSql(
            'UPDATE psa_area SET AREA_HEAD = \'<div class="body">\' WHERE AREA_ID = 121'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            "UPDATE psa_area SET AREA_HEAD = '' WHERE AREA_ID = 121"
        );
        
        $this->addSql(
            'UPDATE psa_area SET AREA_FOOT = \'<div class="body">\' WHERE AREA_ID = 10'
        );
    }
}
