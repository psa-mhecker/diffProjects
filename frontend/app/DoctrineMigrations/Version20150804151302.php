<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150804151302 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Centré' WHERE LABEL_ID = 'CENTRE' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Sans visuel' WHERE LABEL_ID = 'NDP_SANS_VISUEL' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Affichage des CTA' WHERE LABEL_ID = 'NDP_AFFICHAGE_CTA' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'URL de la page d\'abonnement' WHERE LABEL_ID = 'URL_PAGE_ABONNEMENT' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Libellé onglet (Desktop)' WHERE LABEL_ID = 'NDP_TAB_TITRE' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Mode d\'affichage Ouvert / Fermé' WHERE LABEL_ID = 'NDP_MSG_TOGGLE' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label SET LABEL_BO = '1' WHERE LABEL_ID = 'NDP_CLOSED'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Centre' WHERE LABEL_ID = 'CENTRE' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Aucun Visuel' WHERE LABEL_ID = 'NDP_SANS_VISUEL' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Affichage CTA' WHERE LABEL_ID = 'NDP_AFFICHAGE_CTA' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Url Page d\'abonnement' WHERE LABEL_ID = 'URL_PAGE_ABONNEMENT' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Surcharge libellé onglet (Desktop)' WHERE LABEL_ID = 'NDP_TAB_TITRE' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Mode d\'ouverture des toggles à l\'arrivée sur la page :' WHERE LABEL_ID = 'NDP_MSG_TOGGLE' AND LANGUE_ID =1 AND SITE_ID =1");
        $this->addSql("UPDATE psa_label SET LABEL_BO = null WHERE LABEL_ID = 'NDP_CLOSED'");
    }
}
