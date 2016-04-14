<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150622174730 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("update psa_media_format f1 INNER JOIN psa_media_format f2 ON f1.MEDIA_FORMAT_ID= f2.MEDIA_FORMAT_ID  set f1.MEDIA_FORMAT_WIDTH=f2.MEDIA_FORMAT_HEIGHT, f1.MEDIA_FORMAT_HEIGHT=f2.MEDIA_FORMAT_WIDTH");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("update psa_media_format f1 INNER JOIN psa_media_format f2 ON f1.MEDIA_FORMAT_ID= f2.MEDIA_FORMAT_ID  set f1.MEDIA_FORMAT_WIDTH=f2.MEDIA_FORMAT_HEIGHT, f1.MEDIA_FORMAT_HEIGHT=f2.MEDIA_FORMAT_WIDTH");
    }
}
