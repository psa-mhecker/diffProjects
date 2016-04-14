<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150731154818 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("INSERT INTO `psa_page_type` (`PAGE_TYPE_ID`, `PAGE_TYPE_LABEL`, `PAGE_TYPE_CODE`) VALUES (35, 'NDP - Tout Peugeot', 'G07')");
        $this->addSql("INSERT INTO `psa_template_page` (`TEMPLATE_PAGE_ID`, `SITE_ID`, `PAGE_TYPE_ID`, `TEMPLATE_PAGE_LABEL`) VALUES (380, 2, 35, 'NDP_ALL_PEUGEOT_TEMPLATE')");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LARGEUR`) VALUES (380, 150, 1, 4)");
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_ALL_PEUGEOT_TEMPLATE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_PEUGEOT_PRO_DISABLE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_HEADER_GAL_LIGHT", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_HEADER_GAL_VP", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_FOOTER_GAL_VP", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_HEADER_GAL_WEB_LINKS", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_HEADER_GAL_WEB_UP_TO_LINKS", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_SEARCH_CARSTORE", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_BLOC_NUMBER", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_SEARCH_CARSTORE_LABEL", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_ALL_PEUGEOT_REQUIRED", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_SEARCH_CARSTORE_DEALER_REQUIRED", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_PEUGEOT_COUNTRY_VP", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_PEUGEOT_PRO_LABEL", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_MY_PEUGEOT_REQUIRED", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MOBILE_HEADER_GALATIC", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MOBILE_LINK", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MY_PEUGEOT_HEADER_GALATIC", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MY_PEUGEOT_WEB_LABEL", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MY_PEUGEOT_MOB_LABEL", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_FOOTER_GALACTIC", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_FOOTER_GALACTIC_ONLY_VP", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_CALLED_URL", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_URL_WEB_LINK", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_URL_MOB_LINK", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_HEADER_GALACTIC_XML", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_HEADER_GALACTIC_JSON", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_HEADER_GALACTIC_VP_XML", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_HEADER_GALACTIC_VP_JSON", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_MSG_ERROR_UP_TO_3_LINKS_WEB", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_HEADER_AND_FOOTER_GALACTIC", NULL, 2, NULL, NULL, 1, NULL),
            ("NDP_PN10_HEADER_FOOTER_GALACTIQUES", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql("UPDATE psa_label SET LABEL_BO = 1 WHERE LABEL_ID='NDP_ALL_PEUGEOT'");
        $this->addSql('REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_ALL_PEUGEOT_TEMPLATE", 1, 1, "NDP - Tout Peugeot"),
            ("NDP_MSG_PEUGEOT_PRO_DISABLE", 1, 1, "Il est nécessaire de renseigner le lien Peugeot Professionnels dans les sites et webservices PSA pour activer ce lien."),
            ("NDP_MSG_HEADER_GAL_LIGHT", 1, 1, "Le header galactique light complète le header existant des sites de types événementiel et marque."),
            ("NDP_MSG_HEADER_GAL_VP", 1, 1, "Le header galactique du Configurateur VP utilise les mêmes paramétrages."),
            ("NDP_MSG_FOOTER_GAL_VP", 1, 1, "Un footer galactique est également affiché sur le Configurateur VP."),
            ("NDP_MSG_HEADER_GAL_WEB_LINKS", 1, 1, "Header galactique : Liens web"),
            ("NDP_MSG_HEADER_GAL_WEB_UP_TO_LINKS", 1, 1, "Possibilité d’activer jusqu’à 3 liens."),
            ("NDP_MSG_SEARCH_CARSTORE", 1, 1, "Rechercher une concession "),
            ("NDP_BLOC_NUMBER", 1, 1, "Numéro de bloc"),
            ("NDP_SEARCH_CARSTORE_LABEL", 1, 1, "Rechercher une concession"),
            ("NDP_ALL_PEUGEOT", 1, 1, "Tout Peugeot"),
            ("NDP_MSG_ALL_PEUGEOT_REQUIRED", 1, 1, "Il est nécessaire de créer une page utilisant le gabarit Tout Peugeot pour activer ce lien"),
            ("NDP_MSG_SEARCH_CARSTORE_DEALER_REQUIRED", 1, 1, "Il est nécessaire de créer une page utilisant le gabarit Dealer Locator pour activer ce lien."),
            ("NDP_PEUGEOT_COUNTRY_VP", 1, 1, "Peugeot pays VP"),
            ("NDP_PEUGEOT_PRO_LABEL", 1, 1, "Peugeot Pro"),
            ("NDP_MOBILE_HEADER_GALATIC", 1, 1, "Header galactique : Lien mobile"),
            ("NDP_MOBILE_LINK", 1, 1, "Lien mobile"),
            ("NDP_MY_PEUGEOT_HEADER_GALATIC", 1, 1, "Header galactique : Bloc MyPeugeot"),
            ("NDP_MSG_MY_PEUGEOT_REQUIRED", 1, 1, "Il est nécessaire d’activer MyPeugeot dans les Sites et webservices PSA pour afficher le bloc MyPeugeot"),
            ("NDP_URL_WEB_LINK", 1, 1, "Libellé du lien web"),
            ("NDP_URL_MOB_LINK", 1, 1, "Libellé du lien mobile"),
            ("NDP_MY_PEUGEOT_WEB_LABEL", 1, 1, "Libellé"),
            ("NDP_MY_PEUGEOT_MOB_LABEL", 1, 1, "Libellé"),
            ("NDP_FOOTER_GALACTIC", 1, 1, "Footer galactique"),
            ("NDP_FOOTER_GALACTIC_ONLY_VP", 1, 1, "Le footer galactique est uniquement affiché sur le configurateur VP."),
            ("NDP_CALLED_URL", 1, 1, "URL d\'appel"),
            ("NDP_HEADER_GALACTIC_XML", 1, 1, "Header galactique (XML) :"),
            ("NDP_HEADER_GALACTIC_VP_XML", 1, 1, "Header et footer galactiques Configurateur VP (XML) :"),
            ("NDP_HEADER_GALACTIC_VP_JSON", 1, 1, "Header et footer galactiques Configurateur VP (JSON) :"),
            ("NDP_HEADER_GALACTIC_JSON", 1, 1, "Header galactique (JSON) :"),
            ("NDP_HEADER_AND_FOOTER_GALACTIC", 1, 1, "Header et Footer galactique"),
            ("NDP_MSG_ERROR_UP_TO_3_LINKS_WEB", 1, 1, "Vous ne pouvez activer que 3 liens web pour le Header/Footer galactique."),
            ("NDP_PN10_HEADER_FOOTER_GALACTIQUES", 1, 1, "PN10 - Header galactique light_ navigation")'
        );
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'URL mobile' WHERE LABEL_ID='URL_MOB'");
        $this->addSql("INSERT INTO psa_area (AREA_ID,AREA_LABEL,AREA_PATH,AREA_HEAD,AREA_FOOT,AREA_DROPPABLE) VALUES(120,'Peugeot - NDP_HEADER_AND_FOOTER_GALACTIC',NULL,'','',  NULL )");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LARGEUR`, LIGNE) VALUES (150, 120, 3, 4, 3)");
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (840, 1, 'NDP_PN10_HEADER_FOOTER_GALACTIQUES', 0, NULL, 'Cms_Page_Ndp_Pn10HeaderFooterGalactiques', NULL, 0, 0, 0, NULL, NULL, 28, 0, '')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES (4510,'NDP_PN10_HEADER_FOOTER_GALACTIQUES',150,120,840,1,NULL,NULL,NULL,30)");


        $this->addSql("UPDATE psa_liste_webservices set ws_url='https://tools-ndp-dev.peugeot.com/relay/relay.php?url=http://configurateur3d.peugeot.inetpsa.com/CFG3PSite/WsGamme.svc/XML' where ws_id=14");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_ALL_PEUGEOT_TEMPLATE",
                    "NDP_PN10_HEADER_FOOTER_GALACTIQUES",
                    "NDP_MSG_HEADER_GAL_LIGHT",
                    "NDP_MSG_HEADER_GAL_VP",
                    "NDP_MSG_FOOTER_GAL_VP",
                    "NDP_MSG_HEADER_GAL_WEB_LINKS",
                    "NDP_MSG_HEADER_GAL_WEB_UP_TO_LINKS",
                    "NDP_MSG_SEARCH_CARSTORE",
                    "NDP_BLOC_NUMBER",
                    "NDP_SEARCH_CARSTORE_LABEL",
                    "NDP_MSG_ALL_PEUGEOT_REQUIRED",
                    "NDP_MSG_SEARCH_CARSTORE_DEALER_REQUIRED",
                    "NDP_PEUGEOT_COUNTRY_VP",
                    "NDP_PEUGEOT_PRO_LABEL",
                    "NDP_MOBILE_HEADER_GALATIC",
                    "NDP_MOBILE_LINK",
                    "NDP_MY_PEUGEOT_HEADER_GALATIC",
                    "NDP_MSG_MY_PEUGEOT_REQUIRED",
                    "NDP_URL_WEB_LINK",
                    "NDP_URL_MOB_LINK",
                    "NDP_MY_PEUGEOT_WEB_LABEL",
                    "NDP_MSG_ERROR_UP_TO_3_LINKS_WEB",
                    "NDP_MY_PEUGEOT_MOB_LABEL",
                    "NDP_FOOTER_GALACTIC",
                    "NDP_FOOTER_GALACTIC_ONLY_VP",
                    "NDP_MSG_PEUGEOT_PRO_DISABLE",
                    "NDP_CALLED_URL",
                    "NDP_HEADER_GALACTIC_XML",
                    "NDP_HEADER_GALACTIC_VP_XML",
                    "NDP_HEADER_GALACTIC_VP_JSON",
                    "NDP_HEADER_GALACTIC_JSON",
                    "NDP_HEADER_AND_FOOTER_GALACTIC"
                )'
            );
        }
        // supression des area
        $this->addSql('DELETE FROM psa_template_page_area WHERE TEMPLATE_PAGE_ID  = 380 ');
        // supression du template
        $this->addSql("DELETE  FROM `psa_template_page` WHERE TEMPLATE_PAGE_ID  = 380 ");
        //supression du type de gabarit
        $this->addSql("DELETE  FROM `psa_page_type` WHERE PAGE_TYPE_ID  = 35 ");
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID = 4510");
        $this->addSql("DELETE FROM psa_zone where ZONE_ID = 840");

        $this->addSql("DELETE FROM psa_template_page_area where TEMPLATE_PAGE_ID = 150 AND AREA_ID = 120");
        $this->addSql("DELETE FROM psa_area where AREA_ID = 120");
       
    }
}
