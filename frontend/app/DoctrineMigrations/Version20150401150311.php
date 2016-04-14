<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401150311 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC12_3_COLONNES' WHERE `ZONE_ID` = 760");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC16_VERBATIM' WHERE `ZONE_ID` = 768");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC18_CONTENU_GRAND_VISUEL' WHERE `ZONE_ID` = 753");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC19_SLIDESHOW' WHERE `ZONE_ID` = 754");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC2_CONTENU_TEXTE_RICHE' WHERE `ZONE_ID` = 750");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC23_MUR_MEDIA' WHERE `ZONE_ID` = 802");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC33_OFFRE_PLUS' WHERE `ZONE_ID` = 769");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC38_PAGE_404', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pc38Page404' WHERE `ZONE_ID` = 755");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC39_SLIDESHOW_OFFRE' WHERE `ZONE_ID` = 756");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC40_CTA', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pc40Cta' WHERE `ZONE_ID` = 771");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC5_1_COLONNE', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pc5UneColonne' WHERE `ZONE_ID` = 776");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC58_CONTACT' WHERE `ZONE_ID` = 757");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC59_TOOLS' WHERE `ZONE_ID` = 777");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC68_CONTENU_1_ARTICLE_2_OU_3_VISUELS', `ZONE_BO_PATH` = 'TODO' WHERE `ZONE_ID` = 766");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC69_CONTENU_2_COLONNES', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pc69DeuxColonnes' WHERE `ZONE_ID` = 767");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC77_DIMENSION_VEHICULE' WHERE `ZONE_ID` = 758");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PC9_CONTENU_1_ARTICLE_1_VISUEL', `ZONE_FO_PATH` = 'Pc9Contenu1Article1VisuelStrategy', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pc9Contenu1Article1Visuel' WHERE `ZONE_ID` = 752");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PF16_AUTRES_RESEAUX_SOCIAUX', `ZONE_BO_PATH` = 'TODO' WHERE `ZONE_ID` = 762");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PN13_ANCRES' WHERE `ZONE_ID` = 788");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PN2_ONGLET', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pn2Tabs' WHERE `ZONE_ID` = 789");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PN3_TOGGLE_ACCORDEON', `ZONE_BO_PATH` = 'Cms_Page_Ndp_Pn3Toggle' WHERE `ZONE_ID` = 790");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PN7_ENTETE' WHERE `ZONE_ID` = 791");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT15_PLAN_DU_SITE', `ZONE_BO_PATH` = '', `ZONE_TYPE_ID` = 2 WHERE `ZONE_ID` = 792");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT17_CHOIX_LANGUE', `ZONE_BO_PATH` = '', `ZONE_TYPE_ID` = 2 WHERE `ZONE_ID` = 793");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT19_ENGAGEMENTS' WHERE `ZONE_ID` = 795");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT2_FOOTER', `ZONE_BO_PATH` = '', `ZONE_TYPE_ID` = 2 WHERE `ZONE_ID` = 800");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT20_MASTER_PAGE', `ZONE_BO_PATH` = '', `ZONE_TYPE_ID` = 2 WHERE `ZONE_ID` = 796");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT20_QUICK_ACCESS', `ZONE_BO_PATH` = '', `ZONE_TYPE_ID` = 2 WHERE `ZONE_ID` = 797");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT21_NAVIGATION', `ZONE_BO_PATH` = '', `ZONE_TYPE_ID` = 2 WHERE `ZONE_ID` = 798");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PT3_JE_VEUX' WHERE `ZONE_ID` = 801");
        $this->addSql("UPDATE `psa_zone` SET `ZONE_LABEL` = 'NDP_PF14_RESEAUX_SOCIAUX' WHERE `ZONE_ID` = 759");

        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (803, 1, 'NDP_PT2_ADMIN_BESOINAIDE', 0, NULL, 'Cms_Page_Ndp_Pt2BesoinAide', '', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (804, 1, 'NDP_PT2_ADMIN_CTAFOOTER', 0, NULL, 'Cms_Page_Ndp_Pt2CtaFooter', '', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (805, 1, 'NDP_PT2_ADMIN_ELEMENTSLEGAUX', 0, NULL, 'Cms_Page_Ndp_Pt2ElementLegaux', '', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (806, 1, 'NDP_PT2_ADMIN_LAGAMME', 0, NULL, 'Cms_Page_Ndp_Pt2LaGamme', '', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (807, 1, 'NDP_PT2_ADMIN_NEWSLETTER', 0, NULL, 'Cms_Page_Ndp_Pt2Newsletter', '', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (808, 1, 'NDP_PT2_ADMIN_PLANDUSITE', 0, NULL, 'Cms_Page_Ndp_Pt2PlanDuSite', '', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (809, 1, 'NDP_PT2_ADMIN_RESEAUXSOCIAUX', 0, NULL, 'Cms_Page_Ndp_Pt2ReseauxSociaux', '', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (810, 1, 'NDP_PT2_ADMIN_SERVICECLIENT', 0, NULL, 'Cms_Page_Ndp_Pt2ServiceClient', '', 0, 0, 0, NULL, NULL, 28, 0, ''),
            (811, 1, 'NDP_PT20_ADMIN', 0, NULL, 'Cms_Page_Ndp_Pt20', '', 0, 0, 0, NULL, NULL, 28, 0, '')
        ");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_zone where ZONE_id >= 803 AND ZONE_id <= 811");

    }
}
