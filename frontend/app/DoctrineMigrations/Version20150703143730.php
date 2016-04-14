<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150703143730 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // cleaning all area
        $this->addSql('UPDATE psa_area SET AREA_HEAD ="<div class=\"body\">",AREA_FOOT = "</div>"  WHERE AREA_ID= 124');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
     
    }
}
