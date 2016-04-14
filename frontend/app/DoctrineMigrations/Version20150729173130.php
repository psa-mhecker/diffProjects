<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150729173130 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone_template` SET `AREA_ID` = '150',`ZONE_ID` = '821',`ZONE_TEMPLATE_ORDER` = '30',`ZONE_TEMPLATE_MOBILE_ORDER` = NULL ,`ZONE_TEMPLATE_TABLET_ORDER` = NULL ,`ZONE_TEMPLATE_TV_ORDER` = NULL WHERE `psa_zone_template`.`ZONE_TEMPLATE_ID` =3100 LIMIT 1");

        //Ajout tranches dans Gabarit blanc en zone dynamique
        $this->addSql("REPLACE INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`) VALUES
                        (4498, 'NDP_PC2_CONTENU_TEXTE_RICHE', 290, 150, 750,31 , NULL, NULL, NULL, 30),
                        (4499, 'NDP_PC8_DEUX_COLONNES_TEXTE', 290, 150, 751,32 , NULL, NULL, NULL, 30),
                        (4500, 'NDP_PC18_CONTENU_GRAND_VISUEL', 290, 150, 753,33 , NULL, NULL, NULL, 30),
                        (4501, 'NDP_PC19_SLIDESHOW', 290, 150, 754,34 , NULL, NULL, NULL, 30),
                        (4502, 'NDP_PF16_AUTRES_RESEAUX_SOCIAUX', 290, 150, 762,35 , NULL, NULL, NULL, 30),
                        (4503, 'NDP_PF44_DEVENIR_AGENT', 290, 150, 763,36 , NULL, NULL, NULL, 30),
                        (4504, 'NDP_PC36_FAQ', 290, 150, 770,37 , NULL, NULL, NULL, 30),
                        (4505, 'NDP_PC59_TOOLS', 290, 150, 777,38 , NULL, NULL, NULL, 30),
                        (4506, 'NDP_PF27_Car_Picker', 290, 150, 782,39 , NULL, NULL, NULL, 30),
                        (4507, 'NDP_PF8_Webstore_Vehicule_Neuf', 290, 150, 787,40 , NULL, NULL, NULL, 30),
                        (4508, 'NDP_PN13_ANCRES', 290, 150, 788,41 , NULL, NULL, NULL, 30),
                        (4509, 'NDP_PT19_ENGAGEMENTS', 290, 150, 795,42 , NULL, NULL, NULL, 30)");

        //Ajout des traduction de tranches manquantes et maj des noms de tranches 
        
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                        ('NDP_PC12_3_COLONNES', null, 2, null, null, 1, null),
                        ('NDP_PC16_VERBATIM', null, 2, null, null, 1, null),
                        ('NDP_PC18_CONTENU_GRAND_VISUEL', null, 2, null, null, 1, null),
                        ('NDP_PC19_SLIDESHOW', null, 2, null, null, 1, null),
                        ('NDP_PC2_CONTENU_TEXTE_RICHE', null, 2, null, null, 1, null),
                        ('NDP_PC23_MUR_MEDIA', null, 2, null, null, 1, null),
                        ('NDP_PC33_OFFRE_PLUS', null, 2, null, null, 1, null),
                        ('NDP_PC36_FAQ', null, 2, null, null, 1, null),
                        ('NDP_PC38_PAGE_404', null, 2, null, null, 1, null),
                        ('NDP_PC39_SLIDESHOW_OFFRE', null, 2, null, null, 1, null),
                        ('NDP_PC40_CTA', null, 2, null, null, 1, null),
                        ('NDP_PC41_MENTIONS_JURIDIQUES', null, 2, null, null, 1, null),
                        ('NDP_PC42_ACTUALITES', null, 2, null, null, 1, null),
                        ('NDP_PC43_APPLICATIONS_MOBILES', null, 2, null, null, 1, null),
                        ('NDP_PC5_UNE_COLONNE', null, 2, null, null, 1, null),
                        ('NDP_PC58_CONTACT', null, 2, null, null, 1, null),
                        ('NDP_PC59_TOOLS', null, 2, null, null, 1, null),
                        ('NDP_PC60_SUMMARY_AND_SHOWROOM_CTA', null, 2, null, null, 1, null),
                        ('NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS', null, 2, null, null, 1, null),
                        ('NDP_PC69_DEUX_COLONNES', null, 2, null, null, 1, null),
                        ('NDP_PC7_DEUX_COLONNES', null, 2, null, null, 1, null),
                        ('NDP_PC73_MEGA_BANNIERE_DYNAMIQUE', null, 2, null, null, 1, null),
                        ('NDP_PC77_DIMENSION_VEHICULE', null, 2, null, null, 1, null),
                        ('NDP_PC78_MOSAIC_USP', null, 2, null, null, 1, null),
                        ('NDP_PC79_LIGHT_MEDIA_WALL', null, 2, null, null, 1, null),
                        ('NDP_PC8_DEUX_COLONNES_TEXTE', null, 2, null, null, 1, null),
                        ('NDP_PC83_ACCESSORIES_CONTENT', null, 2, null, null, 1, null),
                        ('NDP_PC84_APP_LIST_DYNAMIC_CONTENT', null, 2, null, null, 1, null),
                        ('NDP_PC85_REVOO', null, 2, null, null, 1, null),
                        ('NDP_PC9_UN_ARTICLE_UN_VISUEL', null, 2, null, null, 1, null),
                        ('NDP_PC95_INTERESTED_BY', null, 2, null, null, 1, null),
                        ('NDP_PF11_RECHERCHE_POINT_DE_VENTE', null, 2, null, null, 1, null),
                        ('NDP_PF14_RESEAUX_SOCIAUX', null, 2, null, null, 1, null),
                        ('NDP_PF16_AUTRES_RESEAUX_SOCIAUX', null, 2, null, null, 1, null),
                        ('NDP_PF17_FORM', null, 2, null, null, 1, null),
                        ('NDP_PF2_PRESENTATION_SHOWROOM', null, 2, null, null, 1, null),
                        ('NDP_PF23_RANGE_BAR', null, 2, null, null, 1, null),
                        ('NDP_PF23_RANGEBAR', null, 2, null, null, 1, null),
                        ('NDP_PF25_FILTRES_RESULTATS_CAR_SELECTOR', null, 2, null, null, 1, null),
                        ('NDP_PF27_CAR_PICKER', null, 2, null, null, 1, null),
                        ('NDP_PF30_POPIN_CODE_POSTAL', null, 2, null, null, 1, null),
                        ('NDP_PF42_SELECTEUR_DE_TEINTE_360', null, 2, null, null, 1, null),
                        ('NDP_PF44_DEVENIR_AGENT', null, 2, null, null, 1, null),
                        ('NDP_PF53_ENGINES', null, 2, null, null, 1, null),
                        ('NDP_PF6_DRAG_DROP', null, 2, null, null, 1, null),
                        ('NDP_PF8_Webstore_Vehicule_Neuf', null, 2, null, null, 1, null),
                        ('NDP_PN13_ANCRES', null, 2, null, null, 1, null),
                        ('NDP_PN14_CONFISHOW_NAVIGATION', null, 2, null, null, 1, null),
                        ('NDP_PN15_CONFISHOW_HEADER', null, 2, null, null, 1, null),
                        ('NDP_PN18_IFRAME', null, 2, null, null, 1, null),
                        ('NDP_PN2_ONGLET', null, 2, null, null, 1, null),
                        ('NDP_PN21_FULL_USP', null, 2, null, null, 1, null),
                        ('NDP_PN3_TOGGLE_ACCORDEON', null, 2, null, null, 1, null),
                        ('NDP_PN7_ENTETE', null, 2, null, null, 1, null),
                        ('NDP_PT17_CHOIX_LANGUE', null, 2, null, null, 1, null),
                        ('NDP_PT19_ENGAGEMENTS', null, 2, null, null, 1, null),
                        ('NDP_PT2_FOOTER', null, 2, null, null, 1, null),
                        ('NDP_PT20_ADMIN', null, 2, null, null, 1, null),
                        ('NDP_PT21_NAVIGATION', null, 2, null, null, 1, null),
                        ('NDP_PT22_MY_PEUGEOT', null, 2, null, null, 1, null),
                        ('NDP_PT23_MODULE_QUALIFICATION', null, 2, null, null, 1, null),
                        ('NDP_PT3_JE_VEUX', null, 2, null, null, 1, null),
                        ('NPD_PT2_FOOTER', null, 2, null, null, 1, null),
                        ('NPD_PT22_MY_PEUGEOT', null, 2, null, null, 1, null),
                        ('NPD_PT3_JE_VEUX', null, 2, null, null, 1, null);");
        
        
        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                        ('NDP_PC12_3_COLONNES', 1, 1, 'PC12 - 3 columns media or text_content'),
                        ('NDP_PC16_VERBATIM', 1, 1, 'PC16 - Customer verbatim_content'),
                        ('NDP_PC18_CONTENU_GRAND_VISUEL', 1, 1, 'PC18 - Larg media with small text_content'),
                        ('NDP_PC19_SLIDESHOW', 1, 1, 'PC19 - Homepage slideshow_ratio 16/9 _ content'),
                        ('NDP_PC2_CONTENU_TEXTE_RICHE', 1, 1, 'PC2 - Rich text_content'),
                        ('NDP_PC23_MUR_MEDIA', 1, 1, 'PC23 - Media mosaïc_content'),
                        ('NDP_PC33_OFFRE_PLUS', 1, 1, 'PC33 - Slideshow_ratio cinemascope or 16/9 _ content'),
                        ('NDP_PC36_FAQ', 1, 1, 'PC36 - FAQ _ content'),
                        ('NDP_PC38_PAGE_404', 1, 1, 'PC38 - 404_ navigation'),
                        ('NDP_PC39_SLIDESHOW_OFFRE', 1, 1, 'PC39 - Slideshow_ratio 16 /9 or medium rectangle_content '),
                        ('NDP_PC40_CTA', 1, 1, 'PC40 - CTA_content'),
                        ('NDP_PC41_MENTIONS_JURIDIQUES', 1, 1, 'PC41 - Legals text sticky_desktop only_content'),
                        ('NDP_PC42_ACTUALITES', 1, 1, 'PC42 - News main events_ dynamic content'),
                        ('NDP_PC43_APPLICATIONS_MOBILES', 1, 1, 'PC43 - Apps presentation _ mobile only_dynamic content'),
                        ('NDP_PC5_UNE_COLONNE', 1, 1, 'PC5 - 1  column media or text_ratio XXX_content'),
                        ('NDP_PC58_CONTACT', 1, 1, 'PC58 - CTA with picto_only desktop_dynamic content'),
                        ('NDP_PC59_TOOLS', 1, 1, 'PC59 - CTA _ only mobile_dynamic content'),
                        ('NDP_PC60_SUMMARY_AND_SHOWROOM_CTA', 1, 1, 'PC60 - CTA shooping basket Showroom_dynamic content'),
                        ('NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS', 1, 1, 'PC68 - 1 text and 2 or 3 pictures _content'),
                        ('NDP_PC69_DEUX_COLONNES', 1, 1, 'PC69 - 2  columns  pictures or text  ¾ - ¼_content'),
                        ('NDP_PC7_DEUX_COLONNES', 1, 1, 'PC7 - 2 columns picture or text _content'),
                        ('NDP_PC73_MEGA_BANNIERE_DYNAMIQUE', 1, 1, 'PC73 - Dynamic text message_desktop only_content'),
                        ('NDP_PC77_DIMENSION_VEHICULE', 1, 1, 'PC77 - Slideshow with thumbnail _content'),
                        ('NDP_PC78_MOSAIC_USP', 1, 1, 'PC78 - USP mosaïc_ specific showroom _ content'),
                        ('NDP_PC79_LIGHT_MEDIA_WALL', 1, 1, 'PC79 - Mosaïc media _content'),
                        ('NDP_PC8_DEUX_COLONNES_TEXTE', 1, 1, 'PC8 - 2 columns text 2 icons_content'),
                        ('NDP_PC83_ACCESSORIES_CONTENT', 1, 1, 'PC83 - Accessories – specific confishow – AOA content'),
                        ('NDP_PC84_APP_LIST_DYNAMIC_CONTENT', 1, 1, 'PC84 - Apps list_dynamic content'),
                        ('NDP_PC85_REVOO', 1, 1, 'PC85 - Customer review reevoo_dynamic content'),
                        ('NDP_PC9_UN_ARTICLE_UN_VISUEL', 1, 1, 'PC9 - media slideshow plus text _content'),
                        ('NDP_PC95_INTERESTED_BY', 1, 1, 'PC95 - Interested_By'),
                        ('NDP_PF11_RECHERCHE_POINT_DE_VENTE', 1, 1, 'PF11 - Dealer locator_dynamic content arcad'),
                        ('NDP_PF14_RESEAUX_SOCIAUX', 1, 1, 'PF14 - Social media feeds_only desktop_dynamic content'),
                        ('NDP_PF16_AUTRES_RESEAUX_SOCIAUX', 1, 1, 'PF16 - Social media link_ only desktop _content'),
                        ('NDP_PF17_FORM', 1, 1, 'PF17 - Contact Form template '),
                        ('NDP_PF2_PRESENTATION_SHOWROOM', 1, 1, 'PF2 - Media slideshow header confishow _content'),
                        ('NDP_PF23_RANGE_BAR', 1, 1, 'PF23 - Car range bar_ specific HP_only desktop'),
                        ('NDP_PF23_RANGEBAR', 1, 1, 'PF23 - Car range bar_ specific HP_only desktop'),
                        ('NDP_PF25_FILTRES_RESULTATS_CAR_SELECTOR', 1, 1, 'PF25 - Filter carselector specific_dynamic content'),
                        ('NDP_PF27_CAR_PICKER', 1, 1, 'PF27 - Car picker_content'),
                        ('NDP_PF30_POPIN_CODE_POSTAL', 1, 1, 'PF30 - Post code pop in'),
                        ('NDP_PF42_SELECTEUR_DE_TEINTE_360', 1, 1, 'PF42 - Car color selector _ specific  showroom_AO V3D content'),
                        ('NDP_PF44_DEVENIR_AGENT', 1, 1, 'PF44 - Becoming after sale POS_content'),
                        ('NDP_PF53_ENGINES', 1, 1, 'PF53/PF58 - Trim_Engines_MobileOnly_AO content showroom'),
                        ('NDP_PF6_DRAG_DROP', 1, 1, 'PF6 - Image comparator_content'),
                        ('NDP_PF8_Webstore_Vehicule_Neuf', 1, 1, 'PF8 - Stock _ dynamic content Webstore'),
                        ('NDP_PN13_ANCRES', 1, 1, 'PN13 - Anchor_ desktop only_navigation'),
                        ('NDP_PN14_CONFISHOW_NAVIGATION', 1, 1, 'PN14 - Vertical navigation bar confishow_navigation'),
                        ('NDP_PN15_CONFISHOW_HEADER', 1, 1, 'PN15 - Title confishow_desktop only_navigation'),
                        ('NDP_PN18_IFRAME', 1, 1, 'PN18 - Iframe_content'),
                        ('NDP_PN2_ONGLET', 1, 1, 'PN2 - Thumb index_navigation'),
                        ('NDP_PN21_FULL_USP', 1, 1, 'PN21 - Anchor showrooms USP_desktop only_navigation'),
                        ('NDP_PN3_TOGGLE_ACCORDEON', 1, 1, 'PN3 - Toggle drop down content_ navigation'),
                        ('NDP_PN7_ENTETE', 1, 1, 'PN7 - Title - navigation'),
                        ('NDP_PT17_CHOIX_LANGUE', 1, 1, 'PT17 - Choose language _ bilingual pre-home'),
                        ('NDP_PT19_ENGAGEMENTS', 1, 1, 'PT19 - Peugeot brand committement_ dynamic content'),
                        ('NDP_PT2_FOOTER', 1, 1, 'PT2 - Footer_cross section'),
                        ('NDP_PT20_ADMIN', 1, 1, 'PT20 - Master page_ cross section'),
                        ('NDP_PT21_NAVIGATION', 1, 1, 'PT21 - Horizontal navigation bar _ cross section'),
                        ('NDP_PT22_MY_PEUGEOT', 1, 1, 'PT22 - Expand MyPeugeot _ cross section'),
                        ('NDP_PT23_MODULE_QUALIFICATION', 1, 1, 'PT23 - Personalization tab '),
                        ('NDP_PT3_JE_VEUX', 1, 1, 'PT3 - Drop down expand I want to_desktop only_ cross section'),
                        ('NPD_PT2_FOOTER', 1, 1, 'PT2 - Footer_cross section'),
                        ('NPD_PT22_MY_PEUGEOT', 1, 1, 'PT22 - Expand MyPeugeot _ cross section'),
                        ('NPD_PT3_JE_VEUX', 1, 1, 'PT3 - Drop down expand I want to_desktop only_ cross section');");
        
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
