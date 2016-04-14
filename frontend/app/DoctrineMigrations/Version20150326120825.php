<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150326120825 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
(766, 1, 'NDP_PC68_Contenu_1_Article_2_Ou_3_Visuels', 0, NULL, 'Cms_Page_Ndp_Pc68Contenu1Article2Ou3Visuels', 'Pc68Contenu1Article2Ou3VisuelsStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(767, 1, 'NDP_PC69_Contenu_2_Colonnes', 0, NULL, 'Cms_Page_Ndp_Pc69Contenu2Colonnes', 'Pc69Contenu2ColonnesStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(768, 1, 'NDP_PC16_Verbatim', 0, NULL, 'Cms_Page_Ndp_Pc16Verbatim', 'Pc16VerbatimStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(769, 1, 'NDP_PC33_Offre_Plus', 0, NULL, 'Cms_Page_Ndp_Pc33OffrePlus', 'Pc33OffrePlusStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(770, 1, 'NDP_PC36_FAQ', 0, NULL, 'Cms_Page_Ndp_Pc36FAQ', 'Pc36FAQStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(771, 1, 'NDP_PC40_CTA', 0, NULL, 'Cms_Page_Ndp_Pc40CTA', 'Pc40CTAStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(772, 1, 'NDP_PC41_Mentions_Juridiques', 0, NULL, 'Cms_Page_Ndp_Pc41MentionsJuridiques', 'Pc41MentionsJuridiquesStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(773, 1, 'NDP_PC42_Actualites', 0, NULL, 'Cms_Page_Ndp_Pc42Actualites', 'Pc42ActualitesStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(774, 1, 'NDP_PC43_Applications_Mobiles', 0, NULL, 'Cms_Page_Ndp_Pc43ApplicationsMobiles', 'Pc43ApplicationsMobilesStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(775, 1, 'NDP_PC47_Point_De_Vente_Unique', 0, NULL, 'Cms_Page_Ndp_Pc47PointDeVenteUnique', 'Pc47PointDeVenteUniqueStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(776, 1, 'NDP_PC5_1_Colonne', 0, NULL, 'Cms_Page_Ndp_Pc51Colonne', 'Pc51ColonneStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(777, 1, 'NDP_PC59_Tools', 0, NULL, 'Cms_Page_Ndp_Pc59Tools', 'Pc59ToolsStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(778, 1, 'NDP_PC61_Visuel_Boussole', 0, NULL, 'Cms_Page_Ndp_Pc61VisuelBoussole', 'Pc61VisuelBoussoleStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(779, 1, 'NDP_PC62_Application_Boussole', 0, NULL, 'Cms_Page_Ndp_Pc62ApplicationBoussole', 'Pc62ApplicationBoussoleStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(780, 1, 'NDP_PC63_Lien_Boussole', 0, NULL, 'Cms_Page_Ndp_Pc63LienBoussole', 'Pc63LienBoussoleStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(781, 1, 'NDP_PC7_2_Colonnes', 0, NULL, 'Cms_Page_Ndp_Pc72Colonnes', 'Pc72ColonnesStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(782, 1, 'NDP_PF27_Car_Picker', 0, NULL, 'Cms_Page_Ndp_Pf27CarPicker', 'Pf27CarPickerStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(783, 1, 'NDP_PF32_Filtre_APV', 0, NULL, 'Cms_Page_Ndp_Pf32FiltreAPV', 'Pf32FiltreAPVStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(784, 1, 'NDP_PF42_Selectionneur_De_Teinte_360', 0, NULL, 'Cms_Page_Ndp_Pf42SelectionneurDeTeinte360', 'Pf42SelectionneurDeTeinte360Strategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(785, 1, 'NDP_PF44_Devenir_Agent', 0, NULL, 'Cms_Page_Ndp_Pf44DevenirAgent', 'Pf44DevenirAgentStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(786, 1, 'NDP_PF6_Drag_And_Drop', 0, NULL, 'Cms_Page_Ndp_Pf6DragAndDrop', 'Pf6DragAndDropStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(787, 1, 'NDP_PF8_Webstore_Vehicule_Neuf', 0, NULL, 'Cms_Page_Ndp_Pf8WebstoreVehiculeNeuf', 'Pf8WebstoreVehiculeNeufStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(788, 1, 'NDP_PN13_Ancres', 0, NULL, 'Cms_Page_Ndp_Pn13Ancres', 'Pn13AncresStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(789, 1, 'NDP_PN2_Onglet', 0, NULL, 'Cms_Page_Ndp_Pn2Onglet', 'Pn2OngletStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(790, 1, 'NDP_PN3_Toggle_Accordeon', 0, NULL, 'Cms_Page_Ndp_Pn3ToggleAccordeon', 'Pn3ToggleAccordeonStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(791, 1, 'NDP_PN7_EnTete', 0, NULL, 'Cms_Page_Ndp_Pn7EnTete', 'Pn7EnTeteStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(792, 1, 'NDP_PT15_Plan_Du_Site', 0, NULL, 'Cms_Page_Ndp_Pt15PlanDuSite', 'Pt15PlanDuSiteStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(793, 1, 'NDP_PT17_Choix_De_La_Langue', 0, NULL, 'Cms_Page_Ndp_Pt17ChoixDeLaLangue', 'Pt17ChoixDeLaLangueStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(794, 1, 'NDP_PT18_Pre_Home_Importateur', 0, NULL, 'Cms_Page_Ndp_Pt18PreHomeImportateur', 'Pt18PreHomeImportateurStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(795, 1, 'NDP_PT19_Engagements', 0, NULL, 'Cms_Page_Ndp_Pt19Engagements', 'Pt19EngagementsStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(796, 1, 'NDP_PT20_Master_Page', 0, NULL, 'Cms_Page_Ndp_Pt20MasterPage', 'Pt20MasterPageStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(797, 1, 'NDP_PT20_Quick_Access', 0, NULL, 'Cms_Page_Ndp_Pt20QuickAccess', 'Pt20QuickAccessStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(798, 1, 'NDP_PT21_Navigation', 0, NULL, 'Cms_Page_Ndp_Pt21Navigation', 'Pt21NavigationStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(800, 1, 'NDP_PT2_Footer', 0, NULL, 'Cms_Page_Ndp_Pt2Footer', 'Pt2FooterStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(801, 1, 'NDP_PT3_Je_Veux', 0, NULL, 'Cms_Page_Ndp_Pt3JeVeux', 'Pt3JeVeuxStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')
        ");

        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC8_Contenu_2_Colonnes_Texte' WHERE `ZONE_ID` = 751");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC9_Contenu_1_Article_1_Visuel' WHERE `ZONE_ID` = 752");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC38_Page_404', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pc38PageErreur404', `ZONE_FO_PATH` = 'Pc38PageErreur404Strategy' WHERE `ZONE_ID` = 755");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC39_Slideshow_Offre' WHERE `ZONE_ID` = 756");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC77_Dimension_Vehicule' WHERE `ZONE_ID` = 758");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PF14_Reseaux_Sociaux' WHERE `ZONE_ID` = 759");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC12_3_Colonnes', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pc123Colonnes', `ZONE_FO_PATH` = 'Pc123ColonnesStrategy' WHERE `ZONE_ID` = 760");

        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_LABEL` = 'NDP_PC12_3_Colonnes' WHERE `ZONE_TEMPLATE_ID` = 4107");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_zone where ZONE_id >= 766 AND ZONE_id <= 801");
    }
}
