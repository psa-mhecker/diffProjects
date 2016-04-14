<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150417172925 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("INSERT INTO `psa_reseau_social` VALUES (4,1,2,'FACEBOOK CITROËN',1,5181,'Peugeot',NULL,'https://www.facebook.com/Citroen.France','https://www.facebook.com/Citroen.France',2,1,1,3,NULL),(4,35,2,'FB AL',1,NULL,NULL,NULL,NULL,NULL,1,1,1,3,NULL),(5,1,2,'TWITTER CITROËN FRANCE',2,938,'peugeotfrance','491164946575548416','https://twitter.com/peugeotfrance','https://twitter.com/peugeotfrance',2,1,1,2,NULL),(6,1,2,'YOUTUBE CITROËN footer',3,4097,'theofficialpeugeot',NULL,'http://www.youtube.com/user/CitroenFrance','http://www.youtube.com/user/CitroenFrance',2,1,1,4,NULL),(7,1,2,'PINTEREST CITROËN',4,936,'Peugeot',NULL,'http://www.pinterest.com/peugeotworld/','http://www.pinterest.com/peugeotworld/',2,1,1,6,NULL),(8,1,2,'Instagram',5,5182,'peugeotdanmark',NULL,'http://instagram.com/peugeotdanmark','http://instagram.com/peugeotdanmark',2,1,1,1,NULL),(9,2,2,'Facebook UK',1,NULL,NULL,NULL,NULL,NULL,1,1,1,7,NULL),(10,1,2,'LinkedIn CITROËN',6,934,'peugeot',NULL,'http://www.linkedin.com/company/peugeot','http://www.linkedin.com/company/peugeot',2,1,1,9,NULL),(13,1,2,'YOUTUBE CITROËN PROFESSIONNELS',3,1307,'theofficialpeugeot',NULL,'http://www.youtube.com/user/CitroenFrancePro','http://www.youtube.com/user/CitroenFrancePro',2,1,1,8,NULL),(14,1,5,'Facebook',1,1653,'103666283041018',NULL,'https://www.facebook.com/Citroen.France','https://www.facebook.com/Citroen.France',2,1,1,9,NULL),(15,1,9,'Facebook',1,926,'peugeotfrance',NULL,'https://www.facebook.com/Citroen.France','https://www.facebook.com/Citroen.France',2,1,1,10,NULL),(16,1,9,'test tht',5,NULL,NULL,NULL,NULL,NULL,2,1,NULL,11,NULL),(18,1,2,'YOUTUBE CITROËN (API)',3,924,'thepeugeotofficial',NULL,'https://gdata.youtube.com/feeds/api/videos?author=PeugeotNederland&alt=json&max-results=10&orderby=published&fields=entry(author,title,link,media:group(media:thumbnail))','https://gdata.youtube.com/feeds/api/videos?author=PeugeotFrance&alt=json&max-results=10&orderby=published&fields=entry(author,title,link,media:group(media:thumbnail))',1,1,1,5,NULL),(19,1,5,'TWITTER CITROËN FRANCE',2,2072,'PeugeotFrance','446648036257583104','http://twitter.com/CitroenFrance','http://twitter.com/CitroenFrance',1,1,1,NULL,NULL),(20,1,5,'Youtube PeugeotFrance API',3,1485,'PeugeotFrance',NULL,'https://gdata.youtube.com/feeds/api/videos?author=PeugeotFrance&alt=json&max-results=10&orderby=published&fields=entry(author,title,link,media:group(media:thumbnail))','https://gdata.youtube.com/feeds/api/videos?author=PeugeotFrance&alt=json&max-results=10&orderby=published&fields=entry(author,title,link,media:group(media:thumbnail))',1,1,1,NULL,NULL),(21,1,5,'Intagram',5,932,'peugeotfrance',NULL,'http://instagram.com/peugeotfrance','http://instagram.com/peugeotfrance',2,1,1,NULL,NULL),(22,1,5,'Pinterest',4,1859,'peugeotworld',NULL,'http://www.pinterest.com/peugeotworld/','http://www.pinterest.com/peugeotworld/',2,1,1,NULL,NULL),(23,1,5,'Youtube perso jerome (api)',3,2106,'jeromeforestier','jeromeforestier','https://gdata.youtube.com/feeds/api/videos?author=jeromeforestier&alt=json&max-results=10&orderby=published&fields=entry(author,title,link,media:group(media:thumbnail))','https://gdata.youtube.com/feeds/api/videos?author=jeromeforestier&alt=json&max-results=10&orderby=published&fields=entry(author,title,link,media:group(media:thumbnail))',1,1,1,NULL,NULL),(24,1,5,'Youtube Peugeot France',3,NULL,'PeugeotFrance',NULL,'https://www.youtube.com/user/CitroenFrance','https://www.youtube.com/user/CitroenFrance',1,1,1,NULL,NULL),(25,1,5,'Youtube Peugeot Theofficeal',3,NULL,'theofficialpeugeot',NULL,'https://www.youtube.com/user/theofficialpeugeot','https://www.youtube.com/user/theofficialpeugeot',1,1,1,NULL,NULL),(26,1,5,'Youtube Perso Jerome',3,4097,'jeromeforestier',NULL,'https://www.youtube.com/user/jeromeforestier','https://www.youtube.com/user/jeromeforestier',1,1,1,NULL,NULL),(27,10,60,'test',1,NULL,NULL,NULL,NULL,NULL,1,1,1,NULL,NULL);");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql("DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 4 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 4 AND `psa_reseau_social`.`LANGUE_ID` = 35;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 5 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 6 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 7 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 8 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 9 AND `psa_reseau_social`.`LANGUE_ID` = 2;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 10 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 13 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 14 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 15 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 16 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 18 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 19 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 20 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 21 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 22 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 23 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 24 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 25 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 26 AND `psa_reseau_social`.`LANGUE_ID` = 1;
                        DELETE FROM `psa-ndp`.`psa_reseau_social` WHERE `psa_reseau_social`.`RESEAU_SOCIAL_ID` = 27 AND `psa_reseau_social`.`LANGUE_ID` = 10;");

    }
}
