<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160303084545 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE psa_media_directory SET MEDIA_DIRECTORY_LABEL = "/" WHERE MEDIA_DIRECTORY_ID=1');
        $this->addSql('INSERT INTO psa_media_directory (MEDIA_DIRECTORY_ID, MEDIA_DIRECTORY_PARENT_ID, MEDIA_DIRECTORY_LABEL, MEDIA_DIRECTORY_PATH, SITE_ID, MEDIA_DIRECTORY_ID_PATH)
  SELECT null,1, SITE_CODE_PAYS, CONCAT("> " , SITE_CODE_PAYS),SITE_ID,  null FROM psa_site_code WHERE SITE_ID > 1 AND SITE_CODE_PAYS NOT IN (SELECT MEDIA_DIRECTORY_LABEL FROM psa_media_directory WHERE MEDIA_DIRECTORY_PARENT_ID =1)');
        $this->addSql('UPDATE
    psa_media_directory pmd
    INNER JOIN  (SELECT md.MEDIA_DIRECTORY_ID,md.MEDIA_DIRECTORY_LABEL, md.SITE_ID FROM psa_media_directory md INNER JOIN  (SELECT c1.SITE_CODE_PAYS FROM psa_site_code c1 ) c  ON c.SITE_CODE_PAYS = md.MEDIA_DIRECTORY_LABEL) sub1
    ON sub1.SITE_ID = pmd.SITE_ID
   SET pmd.MEDIA_DIRECTORY_PARENT_ID = sub1.MEDIA_DIRECTORY_ID,  pmd.MEDIA_DIRECTORY_PATH = CONCAT(sub1.MEDIA_DIRECTORY_LABEL,\' > \',pmd.MEDIA_DIRECTORY_LABEL)
WHERE pmd.MEDIA_DIRECTORY_PARENT_ID =1 AND pmd.MEDIA_DIRECTORY_LABEL NOT IN (SELECT c2.SITE_CODE_PAYS FROM psa_site_code c2 )');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

    }
}
