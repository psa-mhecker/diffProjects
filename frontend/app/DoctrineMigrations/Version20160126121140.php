<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160126121140 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_label_langue_site` SET  `LABEL_TRANSLATE` = 'L''utilisation de l''iframe est à EVITER, tant pour des raisons de failles de sécurité, que pour le SEO ou le manque de tracking. Dans tous les cas, il est FORTEMENT conseillé d''afficher un iframe Responsive avec les breaking point suivants : mobile <640px et desktop tablette de 640px à 1280px.' WHERE `LABEL_ID` =  'NDP_MSG_IFRAME_DISPLAY_CONDITION' AND  `LANGUE_ID` =1 AND `SITE_ID` =1");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `psa_label_langue_site` SET  `LABEL_TRANSLATE` = 'L''iFrame ne s’affichera que pour les devices pour lesquels une URL a été renseignée.' WHERE `LABEL_ID` =  'NDP_MSG_IFRAME_DISPLAY_CONDITION' AND  `LANGUE_ID` =1 AND `SITE_ID` =1");
    }
}
