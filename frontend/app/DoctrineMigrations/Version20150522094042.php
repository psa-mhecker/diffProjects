<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150522094042 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (816, 1, 'NDP_PC79_LIGHT_MEDIA_WALL', 0, NULL, 'Cms_Page_Ndp_Pc79MurMediaManuel', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (817, 1, 'NDP_PF53_ENGINES', 0, NULL, 'Cms_Page_Ndp_Pf53FinitionsMotorisations', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (818, 1, 'NDP_PC60_SUMMARY_AND_SHOWROOM_CTA', 0, NULL, 'Cms_Page_Ndp_Pc60RecapitulatifEtCtaShowroom', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (819, 1, 'NDP_PC78_MOSAIC_USP', 0, NULL, 'Cms_Page_Ndp_Pc78UspMosaique', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (820, 1, 'NDP_PC83_ACCESSORIES_CONTENT', 0, NULL, 'Cms_Page_Ndp_Pc83ContenuAccessoires', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (821, 1, 'NDP_PC84_CATALOG_OF_APPLICATIONS', 0, NULL, 'Cms_Page_Ndp_Pc84CatalogueApplications', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (822, 1, 'NDP_PN14_CONFISHOW_NAVIGATION', 0, NULL, 'Cms_Page_Ndp_Pn14NavigationConfiShow', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (823, 1, 'NDP_PN15_CONFISHOW_HEADER', 0, NULL, 'Cms_Page_Ndp_Pn15EnTeteConfiShow', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (824, 1, 'NDP_PN18_IFRAME', 0, NULL, 'Cms_Page_Ndp_Pn18IFrame', NULL, 0, 0, 0, NULL, NULL, 28, 0, ''),
            (825, 1, 'NDP_PN21_FULL_USP', 0, NULL, 'Cms_Page_Ndp_Pn21UspFull', NULL, 0, 0, 0, NULL, NULL, 28, 0, '')
        ");
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_PC79_LIGHT_MEDIA_WALL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PF53_ENGINES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PC60_SUMMARY_AND_SHOWROOM_CTA', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PC78_MOSAIC_USP', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PC83_ACCESSORIES_CONTENT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PC84_CATALOG_OF_APPLICATIONS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PN14_CONFISHOW_NAVIGATION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PN15_CONFISHOW_HEADER', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PN18_IFRAME', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PN21_FULL_USP', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_PC79_LIGHT_MEDIA_WALL', 1, 1, 'Mosaïc media _content'),
            ('NDP_PF53_ENGINES', 1, 1, 'Trim_Engines_MobileOnly_AO content showroom'),
            ('NDP_PC60_SUMMARY_AND_SHOWROOM_CTA', 1, 1, 'CTA shooping basket Showroom_dynamic content'),
            ('NDP_PC78_MOSAIC_USP', 1, 1, 'USP mosaïc_ specific showroom _ content'),
            ('NDP_PC83_ACCESSORIES_CONTENT', 1, 1, 'Accessories – specific confishow – AOA content'),
            ('NDP_PC84_CATALOG_OF_APPLICATIONS', 1, 1, 'Apps list_dynamic content'),
            ('NDP_PN14_CONFISHOW_NAVIGATION', 1, 1, 'Vertical navigation bar confishow_navigation'),
            ('NDP_PN15_CONFISHOW_HEADER', 1, 1, 'Title confishow_desktop only_navigation'),
            ('NDP_PN18_IFRAME', 1, 1, 'Iframe_content'),
            ('NDP_PN21_FULL_USP', 1, 1, 'Anchor showrooms USP_desktop only_navigation')
           ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_zone where ZONE_ID IN(816, 817, 818, 819, 820, 821, 822, 823, 824, 825)");
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_PC79_LIGHT_MEDIA_WALL",
                "NDP_PF53_ENGINES",
                "NDP_PC60_SUMMARY_AND_SHOWROOM_CTA",
                "NDP_PC78_MOSAIC_USP",
                "NDP_PC84_CATALOG_OF_APPLICATIONS",
                "NDP_PC83_ACCESSORIES_CONTENT",
                "NDP_PN14_CONFISHOW_NAVIGATION",
                "NDP_PN15_CONFISHOW_HEADER",
                "NDP_PN18_IFRAME",
                "NDP_PN21_FULL_USP"
                )
            ');
        }
    }
}
