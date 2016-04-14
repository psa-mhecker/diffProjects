<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150928134912 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site set LABEL_TRANSLATE='Showroom VN et Concept car non publiés (ex. : http://showroom.peugeot.com/index.php?showroom_id=9175)' where LABEL_ID='NDP_MIG_URL_TYPE_NOT_PUBLISHED'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site set LABEL_TRANSLATE='Showroom non publié (ex. : http://showroom.peugeot.com/index.php?showroom_id=9175)' where LABEL_ID='NDP_MIG_URL_TYPE_NOT_PUBLISHED'");
    }
}
