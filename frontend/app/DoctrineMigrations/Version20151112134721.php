<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151112134721 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_template_page` (`TEMPLATE_PAGE_ID`,`SITE_ID`,`PAGE_TYPE_ID`,`TEMPLATE_PAGE_LABEL`,`TEMPLATE_PAGE_GENERAL`) VALUES
                        (1504,2,15,'AGILE - Services - Sprint1',NULL),
                        (1505,2,15,'AGILE - Showroom – Sprint 1',NULL),
                        (1510,2,15,'AGILE - HP – Sprint 1 - NavFull',NULL),
                        (1511,2,15,'AGILE - HP – Sprint 1 - NavLight',NULL),
                        (1513,2,15,'AGILE - DEMO - Sprint1 - NavFull',NULL),
                        (1515,2,15,'AGILE - DEMO - Sprint1 - NavLight',NULL)");
        $this->addSql("INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`,`ZONE_TEMPLATE_LABEL`,`TEMPLATE_PAGE_ID`,`AREA_ID`,`ZONE_ID`,`ZONE_TEMPLATE_ORDER`,`ZONE_TEMPLATE_MOBILE_ORDER`,`ZONE_TEMPLATE_TABLET_ORDER`,`ZONE_TEMPLATE_TV_ORDER`,`ZONE_CACHE_TIME`) VALUES
                        (6001,'PC5',1504,150,776,1,1,NULL,NULL,-2),
                        (6002,'PC7',1504,150,781,2,2,NULL,NULL,-2),
                        (6003,'PC12',1504,150,760,3,3,NULL,NULL,-2),
                        (6021,'PF2',1505,148,830,1,1,NULL,NULL,-2),
                        (6006,'PT21',1510,10,798,1,1,NULL,NULL,-2),
                        (6007,'PN7',1510,121,791,2,2,NULL,NULL,-2),
                        (6008,'PT21 Light',1511,10,798,1,1,NULL,NULL,-2),
                        (6009,'PN7',1511,121,791,2,2,NULL,NULL,-2),
                        (6010,'PT21',1513,10,798,1,1,NULL,NULL,-2),
                        (6011,'PN7',1513,121,791,2,2,NULL,NULL,-2),
                        (6012,'PC5',1513,150,776,3,NULL,NULL,NULL,-2),
                        (6013,'PC7',1513,150,781,4,NULL,NULL,NULL,-2),
                        (5014,'PC12',1513,150,760,5,NULL,NULL,NULL,-2),
                        (6015,'PT21',1515,10,798,1,1,NULL,NULL,-2),
                        (6016,'PN7',1515,121,791,2,2,NULL,NULL,-2),
                        (6017,'PC5',1515,150,776,4,NULL,NULL,NULL,-2),
                        (6018,'PC7',1515,150,781,5,NULL,NULL,NULL,-2),
                        (6019,'PC12',1515,150,760,6,NULL,NULL,NULL,-2),
                        (6020,'PF2',1515,121,830,3,NULL,NULL,NULL,-2)");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`,`AREA_ID`,`TEMPLATE_PAGE_AREA_ORDER`,`LIGNE`,`COLONNE`,`LARGEUR`,`HAUTEUR`,`IS_DROPPABLE`) VALUES
                        (1504,150,1,1,1,4,1,0),
                        (1505,148,1,1,1,4,1,0),
                        (1510,10,1,1,1,4,1,0),
                        (1510,121,1,2,1,4,1,0),
                        (1511,10,1,1,1,4,1,0),
                        (1511,121,1,2,1,4,1,0),
                        (1513,10,1,1,1,4,1,0),
                        (1513,121,1,2,1,4,1,0),
                        (1513,150,1,3,1,4,1,0),
                        (1515,10,1,1,1,4,1,0),
                        (1515,121,1,2,1,4,1,0),
                        (1515,150,1,3,1,4,1,0)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_template_page_area','psa_zone_template','psa_template_page');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  TEMPLATE_PAGE_ID IN (1504,1505,1510,1511,1513,1515)
            ');
        }

    }
}
