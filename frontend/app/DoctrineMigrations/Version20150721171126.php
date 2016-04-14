<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150721171126 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Showroom VN publié (ex. : http://www.peugeot.fr/decouvrir/nouvelle-308/5-portes/)' WHERE LABEL_ID ='NDP_MIG_SHOWROOM_TYPE_VN_PUBLISHED' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Showroom Concept car publié (ex. : http://www.peugeot.fr/concept-cars-showroom/exalt-car/concept-car/)' WHERE LABEL_ID ='NDP_MIG_SHOWROOM_TYPE_CONCEPT_PUBLISHED' AND SITE_ID = 1 AND LANGUE_ID = 1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Rubrique Technologie publiée (ex. : http://www.peugeot.fr/technologies/)' WHERE LABEL_ID ='NDP_MIG_SHOWROOM_TYPE_TECHNO_PUBLISHED' AND SITE_ID = 1 AND LANGUE_ID = 1");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

    }
}
