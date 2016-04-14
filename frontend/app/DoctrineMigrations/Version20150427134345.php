<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150427134345 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE psa_template_page_area SET TEMPLATE_PAGE_AREA_ORDER = 3 WHERE TEMPLATE_PAGE_ID =363 AND  AREA_ID =150");
        $this->addSql("UPDATE psa_template_page_area SET TEMPLATE_PAGE_AREA_ORDER = 2 WHERE TEMPLATE_PAGE_ID =363 AND  AREA_ID =148");
        $this->addSql("UPDATE psa_template_page_area SET TEMPLATE_PAGE_AREA_ORDER = 4 WHERE TEMPLATE_PAGE_ID =363 AND  AREA_ID =122");
        $this->addSql("UPDATE psa_template_page_area SET TEMPLATE_PAGE_AREA_ORDER = 1 WHERE TEMPLATE_PAGE_ID =363 AND  AREA_ID =121");

        $this->addSql("DELETE FROM psa_zone_template WHERE TEMPLATE_PAGE_ID = 363 AND ZONE_ID = 773");
        $this->addSql("DELETE FROM psa_zone_template WHERE TEMPLATE_PAGE_ID = 363 AND ZONE_ID = 756");
        $this->addSql("DELETE FROM psa_zone_template WHERE TEMPLATE_PAGE_ID = 363 AND ZONE_ID = 812");
        $this->addSql("DELETE FROM psa_zone_template WHERE TEMPLATE_PAGE_ID = 363 AND ZONE_ID = 774");
        $this->addSql("DELETE FROM psa_zone_template WHERE TEMPLATE_PAGE_ID = 363 AND ZONE_ID = 782");

        $this->addSql("UPDATE  psa_page SET PAGE_CURRENT_VERSION = 1, PAGE_DRAFT_VERSION = 1, PAGE_CREATION_USER = '' WHERE  PAGE_ID =1 AND  LANGUE_ID =1");

        $this->addSql("DELETE FROM psa_page_zone_media where PAGE_ID = 1");
        $this->addSql("DELETE FROM psa_page_zone_cta_cta where PAGE_ID = 1");
        $this->addSql("DELETE FROM psa_page_zone_cta where PAGE_ID = 1");
        $this->addSql("DELETE FROM psa_page_zone where PAGE_ID = 1");
        $this->addSql("DELETE FROM psa_page_version where PAGE_ID = 1");
        $this->addSql("DELETE FROM psa_page where PAGE_ID = 1 AND  LANGUE_ID !=1");

        $this->addSql("INSERT INTO psa_page_version (PAGE_ID, LANGUE_ID, PAGE_VERSION, STATE_ID, TEMPLATE_PAGE_ID, PAGE_TITLE_BO, PAGE_TITLE, PAGE_SUBTITLE, PAGE_TEXT, PAGE_SHORTTEXT, PAGE_CODE, PAGE_URL, PAGE_DATE, MEDIA_ID, PAGE_CLEAR_URL, PAGE_PICTO_URL, PAGE_TITLE_URL, PAGE_EXTERNAL_LINK, PAGE_META_TITLE, PAGE_META_DESC, PAGE_META_KEYWORD, PAGE_META_ROBOTS, PAGE_META_URL_CANONIQUE, PAGE_START_DATE, PAGE_END_DATE, PAGE_PUBLICATION_DATE, PAGE_VERSION_CREATION_DATE, PAGE_VERSION_CREATION_USER, PAGE_VERSION_UPDATE_DATE, PAGE_VERSION_UPDATE_USER, PAGE_AUTHOR, PAGE_LATITUDE, PAGE_LONGITUDE, PAGE_DISPLAY, PAGE_DISPLAY_SEARCH, PAGE_DISPLAY_NAV, PAGE_KEYWORD, PUB_ID, PAGE_PROTOCOLE_HTTPS, PAGE_MODE_AFFICHAGE, PAGE_URL_EXTERNE, PAGE_URL_EXTERNE_MODE_OUVERTURE, PAGE_TYPE_EXPAND, PAGE_OUVRIR_NIVEAU_3, PAGE_NB_ITEM_PAR_LIGNE, PAGE_MENTIONS_LEGALES, MEDIA_ID2, PAGE_GAMME_VEHICULE, PAGE_VEHICULE, PAGE_PERSO, PAGE_LANGUETTE_PRO, PAGE_LANGUETTE_CLIENT, PAGE_OUVERTURE_DIRECT, PAGE_DISPLAY_PLAN) VALUES
    (1, 1, 1, 4, 363, 'Accueil', 'Accueil', NULL, 'lorem', NULL, NULL, NULL, NULL, 4643, '/fr/accueil-fr.html', NULL, NULL, NULL, 'Accueil SITE FR RECETTE', 'Accueil5', 'Accueil4', 2, NULL, NULL, NULL, '2013-09-10', '2007-12-01 00:00:00', 'admin', '2015-01-18 22:22:54', 'admin', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1)");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
