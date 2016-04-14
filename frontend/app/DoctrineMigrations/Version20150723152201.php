<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150723152201 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //  modification messages d'erreur du titre long :

        $this->addSql(
            'UPDATE `psa_label_langue_site` SET LABEL_TRANSLATE = "Vous ne pouvez saisir que 255 caractères maximum pour le Titre long" WHERE LABEL_ID = "ALERT_PAGE_TITLE_LONG_MAX"'
        );
        $this->addSql(
            'UPDATE `psa_label_langue_site` SET LABEL_TRANSLATE = "Le titre de la page est trop long,il est conseillé de ne pas dépasser 50 caractères pour un titre long" WHERE LABEL_ID = "ALERT_CNT_TITLE_LONG_MAX"'
        );
    }
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
