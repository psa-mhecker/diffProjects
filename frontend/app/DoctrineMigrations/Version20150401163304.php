<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401163304 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_page_zone_multi WHERE zone_template_id IN ( 1782, 1783, 1784, 1785, 1786, 1787, 1788, 1789, 1790, 1791, 1792, 1793, 1794, 1955, 2061, 2314)");
        $this->addSql("DELETE FROM psa_page_zone WHERE zone_template_id IN ( 1782, 1783, 1784, 1785, 1786, 1787, 1788, 1789, 1790, 1791, 1792, 1793, 1794, 1955, 2061, 2314)");
        $this->addSql("DELETE FROM psa_page_version WHERE PAGE_ID=2");
        $this->addSql("DELETE FROM psa_zone_template WHERE TEMPLATE_PAGE_ID = 150");

        $this->addSql("UPDATE  psa_page SET  PAGE_CURRENT_VERSION =  '1', PAGE_DRAFT_VERSION =  '1', PAGE_CREATION_USER =  '' WHERE  PAGE_ID =2 AND  LANGUE_ID =1");
        $this->addSql("INSERT INTO `psa_page_version` (`PAGE_ID`, `LANGUE_ID`, `PAGE_VERSION`, `STATE_ID`, `TEMPLATE_PAGE_ID`, `PAGE_TITLE_BO`, `PAGE_TITLE`, `PAGE_SUBTITLE`, `PAGE_TEXT`, `PAGE_SHORTTEXT`, `PAGE_CODE`, `PAGE_URL`, `PAGE_DATE`, `MEDIA_ID`, `PAGE_CLEAR_URL`, `PAGE_PICTO_URL`, `PAGE_TITLE_URL`, `PAGE_EXTERNAL_LINK`, `PAGE_META_TITLE`, `PAGE_META_DESC`, `PAGE_META_KEYWORD`, `PAGE_META_ROBOTS`, `PAGE_META_URL_CANONIQUE`, `PAGE_START_DATE`, `PAGE_END_DATE`, `PAGE_PUBLICATION_DATE`, `PAGE_VERSION_CREATION_DATE`, `PAGE_VERSION_CREATION_USER`, `PAGE_VERSION_UPDATE_DATE`, `PAGE_VERSION_UPDATE_USER`, `PAGE_AUTHOR`, `PAGE_LATITUDE`, `PAGE_LONGITUDE`, `PAGE_DISPLAY`, `PAGE_DISPLAY_SEARCH`, `PAGE_DISPLAY_NAV`, `PAGE_KEYWORD`, `PUB_ID`, `PAGE_PROTOCOLE_HTTPS`, `PAGE_MODE_AFFICHAGE`, `PAGE_URL_EXTERNE`, `PAGE_URL_EXTERNE_MODE_OUVERTURE`, `PAGE_TYPE_EXPAND`, `PAGE_OUVRIR_NIVEAU_3`, `PAGE_NB_ITEM_PAR_LIGNE`, `PAGE_MENTIONS_LEGALES`, `MEDIA_ID2`, `PAGE_GAMME_VEHICULE`, `PAGE_VEHICULE`, `PAGE_PERSO`, `PAGE_LANGUETTE_PRO`, `PAGE_LANGUETTE_CLIENT`, `PAGE_OUVERTURE_DIRECT`, `PAGE_DISPLAY_PLAN`) VALUES
          (2, 1, 1, 4, 150, '- Général -', '- Général -', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/fr/general.html', NULL, NULL, NULL, '- Général -', NULL, NULL, NULL, NULL, NULL, NULL, '2013-02-13', '2012-09-01 00:00:00', 'admin', '2014-03-11 18:17:14', 'u402471', NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)
          ");

        $this->addSql("INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`) VALUES
            (1, 'NDP_PT2_ADMIN_BESOINAIDE', 150, 122, 803, 1, NULL, NULL, NULL, 30),
            (2, 'NDP_PT2_ADMIN_LAGAMME', 150, 122, 806, 2, NULL, NULL, NULL, 30),
            (3, 'NDP_PT2_ADMIN_NEWSLETTER', 150, 122, 807, 3, NULL, NULL, NULL, 30),
            (4, 'NDP_PT2_ADMIN_SERVICECLIENT', 150, 122, 810, 4, NULL, NULL, NULL, 30),
            (5, 'NDP_PT2_ADMIN_RESEAUXSOCIAUX', 150, 122, 809, 5, NULL, NULL, NULL, 30),
            (6, 'NDP_PT2_ADMIN_CTAFOOTER', 150, 122, 804, 6, NULL, NULL, NULL, 30),
            (7, 'NDP_PT2_ADMIN_PLANDUSITE', 150, 122, 808, 7, NULL, NULL, NULL, 30),
            (8, 'NDP_PT2_ADMIN_ELEMENTSLEGAUX', 150, 122, 805, 8, NULL, NULL, NULL, 30)
            ");
    }


    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
