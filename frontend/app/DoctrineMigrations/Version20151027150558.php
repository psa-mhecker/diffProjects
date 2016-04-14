<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151027150558 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        // supression des media de type youtube inexistant dans le projet
        $this->addSql('DELETE m.* FROM psa_media m WHERE m.MEDIA_TYPE_ID="youtube"');
        // deplacer les medias d'un dossier impossible dans le dossier racine
        $this->addSql('update  psa_media m INNER JOIN psa_media_directory md ON  m.MEDIA_DIRECTORY_ID=md.MEDIA_DIRECTORY_ID set m.MEDIA_DIRECTORY_ID=1 WHERE md.SITE_ID = 1');
        // supression des dossiers inutile
        $this->addSql('delete  md.* FROM psa_media_directory md WHERE SITE_ID =1');
        // mise a jour des ancies dossier racine par pays
        $this->addSql('UPDATE psa_media_directory SET MEDIA_DIRECTORY_LABEL="CT" WHERE MEDIA_DIRECTORY_ID =1');
        $this->addSql('UPDATE psa_media_directory SET MEDIA_DIRECTORY_PARENT_ID = nULl  WHERE SITE_ID>2 AND MEDIA_DIRECTORY_PARENT_ID = 1');
        $this->addSql('UPDATE psa_media_directory SET MEDIA_DIRECTORY_PATH= MEDIA_DIRECTORY_LABEL WHERE MEDIA_DIRECTORY_PARENT_ID IS NULL;');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
