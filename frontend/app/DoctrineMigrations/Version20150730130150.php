<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150730130150 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE = "L\'affichage de cette présentation peut être paramétré jusqu\'à la date du Reveal. Dans ce cas, la date de fin de publication de la page doit correspondre à la date du Reveal.
Une Welcome Page Reveal / Annonce du lancement commercial doit également être programmée pour s\'afficher à la fin de publication de la Welcome Page Annonce du Reveal." WHERE LABEL_ID ="NDP_MSG_ANNOUNCEMENT_REVEAL" AND SITE_ID = 1 AND LANGUE_ID = 1');
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE = "Date du Reveal" WHERE LABEL_ID ="NDP_ANNOUNCEMENT_REVEAL_DATE" AND SITE_ID = 1 AND LANGUE_ID = 1');
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE = "Date du lancement commercial" WHERE LABEL_ID ="NDP_ANNOUNCEMENT_LAUNCH_DATE" AND SITE_ID = 1 AND LANGUE_ID = 1');
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE = "Date du lancement commercial" WHERE LABEL_ID ="NDP_MARKETING_DATE" AND SITE_ID = 1 AND LANGUE_ID = 1');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
