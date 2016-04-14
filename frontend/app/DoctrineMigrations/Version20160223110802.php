<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160223110802 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE `psa_area` SET AREA_HEAD = \'<div class="row" ><div class="small-12 medium-12 large-12 columns" id="leftInfoContainer">\', AREA_FOOT = "</div>" WHERE AREA_ID = 153');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE `psa_area` SET AREA_HEAD = \'<div class="row" ><div class="small-12 large-12 columns" id="leftInfoContainer">\', AREA_FOOT = "</div>" WHERE AREA_ID = 153');
    }

}
