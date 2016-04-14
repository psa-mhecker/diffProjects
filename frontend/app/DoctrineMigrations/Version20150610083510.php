<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150610083510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //creation site
        $this->addSql("INSERT INTO `psa_site` VALUES (3,'Peugeot UK','en.psa-ndp.com','','Peugeot  UK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,1,NULL,NULL,0,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'admin',1,'adminAL83','no_return_address@mpsa.com','peugeot-en@yopmail.com',3,3,0,0,90,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)");
        //site code
        $this->addSql("INSERT INTO `psa_site_code` VALUES (3,'EN','LDEN')");
        // site dns
        $this->addSql("INSERT INTO `psa_site_dns` VALUES (3,'recette-en.psa-ndp.com'),(3,'en.psa-ndp.com')");
        // langue
        $this->addSql("INSERT INTO `psa_site_language` VALUES (3,2)");
        // Ajout des directory
        $this->addSql('INSERT INTO `psa_directory_site` VALUES (1,3),(4,3),(27,3),(28,3),(35,3),(36,3),(42,3),(43,3),(62,3),(76,3),(80,3),(81,3),(82,3),(83,3),(84,3),(85,3),(86,3),(87,3),(88,3),(89,3),(90,3),(91,3),(92,3),(93,3),(94,3),(95,3),(96,3),(97,3),(98,3),(99,3),(100,3),(101,3),(102,3),(103,3),(104,3),(105,3),(106,3),(107,3),(108,3),(109,3),(110,3),(111,3),(112,3),(113,3),(114,3),(115,3),(116,3),(117,3),(118,3),(119,3),(120,3),(121,3),(122,3),(182,3),(183,3),(185,3),(186,3),(188,3),(189,3),(190,3),(191,3),(192,3),(198,3),(199,3),(200,3),(201,3),(202,3),(204,3),(205,3),(212,3),(221,3),(223,3),(233,3)');
        // media directory
        $this->addSql("INSERT INTO `psa_media_directory` VALUES (485,1,'EN','Racine > EN',3,NULL)");
        // content type site
        $this->addSql("INSERT INTO `psa_content_type_site` VALUES (2,3,NULL,NULL,NULL,NULL),(3,3,NULL,NULL,NULL,NULL),(4,3,NULL,NULL,NULL,NULL),(5,3,NULL,NULL,NULL,NULL),(6,3,NULL,NULL,NULL,NULL)");
        // creation page general et accueil
        $this->addSql("INSERT INTO `psa_page` VALUES (3028,2,NULL,3,NULL,NULL,1,NULL,NULL,1,0,'3028','3028|Page Generale',-1,1,NULL,NULL,NULL),(3029,2,NULL,3,NULL,NULL,1,NULL,NULL,1,0,'3029','3029|Accueil',0,0,NULL,NULL,NULL) ");
        $this->addSql("INSERT INTO `psa_page_version` VALUES (3028,2,1,1,150,'Page Generale','Page Generale',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3029,2,1,1,363,'Accueil','Accueil',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)");
        // profile utilisateur
        $this->addSql("INSERT INTO `psa_profile` VALUES (13,'ADMINISTRATEUR',0,3),(14,'CONTENTMASTER',0,3),(15,'CONTRIBUTEUR',0,3),(16,'TRADUCTEUR',0,3),(17,'WEBMASTER',0,3)");
        $this->addSql("INSERT INTO `psa_profile_directory` VALUES (13,1,13007),(13,4,13015),(13,27,13001),(13,28,13006),(13,35,13003),(13,36,13004),(13,42,13002),(13,43,13005),(13,62,13019),(13,76,13043),(13,80,13030),(13,81,13031),(13,82,13020),(13,83,13024),(13,84,13025),(13,85,13026),(13,86,13027),(13,87,13028),(13,88,13029),(13,89,13032),(13,90,13033),(13,91,13034),(13,92,13035),(13,93,13036),(13,94,13044),(13,95,13045),(13,96,13046),(13,97,13047),(13,98,13048),(13,99,13049),(13,100,13050),(13,101,13051),(13,102,13052),(13,103,13053),(13,104,13054),(13,105,13055),(13,106,13056),(13,107,13057),(13,108,13058),(13,109,13059),(13,110,13060),(13,111,13061),(13,112,13062),(13,113,13063),(13,114,13064),(13,115,13065),(13,116,13066),(13,117,13067),(13,118,13069),(13,119,13068),(13,120,13070),(13,121,13071),(13,122,13072),(13,182,13016),(13,183,13017),(13,185,13008),(13,186,13012),(13,188,13013),(13,189,13009),(13,190,13010),(13,191,13011),(13,192,13022),(13,198,13037),(13,199,13038),(13,200,13039),(13,201,13040),(13,202,13021),(13,204,13041),(13,205,13014),(13,212,13018),(13,221,13042),(13,223,13073),(13,233,13023),(14,1,14077),(14,4,14080),(14,27,14074),(14,28,14076),(14,42,14075),(14,62,14082),(14,182,14081),(14,185,14079),(14,191,14078),(14,192,14085),(14,202,14083),(14,233,14084),(15,27,15086),(15,28,15088),(15,42,15087),(16,1,16091),(16,4,16092),(16,27,16089),(16,42,16090),(16,182,16093),(17,1,17099),(17,4,17105),(17,27,17096),(17,28,17098),(17,35,17115),(17,36,17117),(17,42,17097),(17,43,17116),(17,62,17111),(17,119,17094),(17,120,17095),(17,182,17106),(17,183,17107),(17,185,17100),(17,186,17101),(17,187,17104),(17,188,17103),(17,189,17109),(17,190,17110),(17,191,17108),(17,192,17113),(17,202,17112),(17,205,17102),(17,233,17114)");
        $this->addSql("INSERT INTO `psa_user_profile` VALUES ('admin',13),('admin',14),('admin',15),('admin',16),('admin',17) ");
        $this->addSql("INSERT INTO `psa_user_role` VALUES ('admin',7,2,3),('admin',7,3,3),('admin',7,4,3),('admin',7,5,3),('admin',7,6,3)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM `psa_user_profile` WHERE PROFILE_ID IN (13,14,15,16,17)');
        $this->addSql('DELETE FROM `psa_user_role` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_profile_directory` WHERE PROFILE_ID IN (13,14,15,16,17)');
        $this->addSql('DELETE FROM `psa_profile` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_page_version` WHERE PAGE_ID IN (SELECT p.PAGE_ID FROM psa_page p WHERE p.SITE_ID=3 ) ');
        $this->addSql('DELETE FROM `psa_page` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_media_directory` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_content_type_site` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_directory_site` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_site_language` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_site_dns` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_site_code` WHERE SITE_ID=3');
        $this->addSql('DELETE FROM `psa_site` WHERE SITE_ID=3');
    }
}
