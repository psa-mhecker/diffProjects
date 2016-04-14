<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160201164400 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT =500 WHERE MEDIA_FORMAT_LABEL="NDP_MEDIA_CONTENT_ONE_COLUMN" ');
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_WIDtH =640, MEDIA_FORMAT_HEIGHT=480, MEDIA_FORMAT_RATIO=1.33 WHERE MEDIA_FORMAT_LABEL="NDP_MEDIA_CONTENT_ONE_COLUMN_MOBILE" ');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_HEIGHT =0 WHERE MEDIA_FORMAT_LABEL="NDP_MEDIA_CONTENT_ONE_COLUMN" ');
        $this->addSql('UPDATE psa_media_format SET MEDIA_FORMAT_WIDtH =640, MEDIA_FORMAT_HEIGHT=218, MEDIA_FORMAT_RATIO=2.98 WHERE MEDIA_FORMAT_LABEL="NDP_MEDIA_CONTENT_ONE_COLUMN_MOBILE" ');


    }
}
