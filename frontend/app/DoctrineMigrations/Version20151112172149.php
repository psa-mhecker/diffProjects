<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151112172149 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("REPLACE INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`,`AREA_ID`,`TEMPLATE_PAGE_AREA_ORDER`,`LIGNE`,`COLONNE`,`LARGEUR`,`HAUTEUR`,`IS_DROPPABLE`) VALUES
                        (1504,150,1,1,1,4,1,0),
                        (1505,148,1,1,1,4,1,0),
                        (1510,10,1,1,1,4,1,0),
                        (1510,121,2,2,1,4,1,0),
                        (1511,10,1,1,1,4,1,0),
                        (1511,121,2,2,1,4,1,0),
                        (1513,10,1,1,1,4,1,0),
                        (1513,121,2,2,1,4,1,0),
                        (1513,150,3,3,1,4,1,0),
                        (1515,10,1,1,1,4,1,0),
                        (1515,121,2,2,1,4,1,0),
                        (1515,150,3,3,1,4,1,0)");
        $this->addSql("UPDATE `psa_page_type` set PAGE_TYPE_UNIQUE = null where PAGE_TYPE_ID=33");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_page_type` set PAGE_TYPE_UNIQUE = 1 where PAGE_TYPE_ID=33");
    }
}
