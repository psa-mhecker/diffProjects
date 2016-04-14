<?php

namespace Application\Migrations;

use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160229104803 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("set foreign_key_checks=0");

        //AGILE - NDP_TP_SHOWROOM
        $this->addSql("DELETE FROM `psa_zone_template`  WHERE `ZONE_TEMPLATE_ID`= 6144");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 20, `ZONE_TEMPLATE_MOBILE_ORDER`= 20  WHERE `ZONE_TEMPLATE_ID`= 6145");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 21, `ZONE_TEMPLATE_MOBILE_ORDER`= 21 WHERE `ZONE_TEMPLATE_ID`= 6127");

        //AGILE - NDP_TP_TECHNO
        $this->addSql("DELETE FROM `psa_zone_template`  WHERE `ZONE_TEMPLATE_ID`= 6255");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 9, `ZONE_TEMPLATE_MOBILE_ORDER`= 9  WHERE `ZONE_TEMPLATE_ID`= 6256");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 10, `ZONE_TEMPLATE_MOBILE_ORDER`= 10  WHERE `ZONE_TEMPLATE_ID`= 6257");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 11, `ZONE_TEMPLATE_MOBILE_ORDER`= 11  WHERE `ZONE_TEMPLATE_ID`= 6258");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 12, `ZONE_TEMPLATE_MOBILE_ORDER`= 12  WHERE `ZONE_TEMPLATE_ID`= 6259");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 13, `ZONE_TEMPLATE_MOBILE_ORDER`= 13  WHERE `ZONE_TEMPLATE_ID`= 6260");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 14, `ZONE_TEMPLATE_MOBILE_ORDER`= 14  WHERE `ZONE_TEMPLATE_ID`= 6261");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 15, `ZONE_TEMPLATE_MOBILE_ORDER`= 15  WHERE `ZONE_TEMPLATE_ID`= 6262");

        //AGILE - Home page
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL` = 'AGILE - NDP_TP_HOME_PAGE' WHERE `TEMPLATE_PAGE_ID` = 1530");
        $this->addSql("DELETE FROM `psa_zone_template`  WHERE `ZONE_TEMPLATE_ID` IN (6072, 6073)");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 4, `ZONE_TEMPLATE_MOBILE_ORDER`= 4  WHERE `ZONE_TEMPLATE_ID`= 6070");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 5, `ZONE_TEMPLATE_MOBILE_ORDER`= 5  WHERE `ZONE_TEMPLATE_ID`= 6076");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 6, `ZONE_TEMPLATE_MOBILE_ORDER`= 6  WHERE `ZONE_TEMPLATE_ID`= 6075");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 7, `ZONE_TEMPLATE_MOBILE_ORDER`= 7  WHERE `ZONE_TEMPLATE_ID`= 6071");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 8, `ZONE_TEMPLATE_MOBILE_ORDER`= 8  WHERE `ZONE_TEMPLATE_ID`= 6074");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 9, `ZONE_TEMPLATE_MOBILE_ORDER`= 9  WHERE `ZONE_TEMPLATE_ID`= 6079");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 10, `ZONE_TEMPLATE_MOBILE_ORDER`= 10  WHERE `ZONE_TEMPLATE_ID`= 6080");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 11, `ZONE_TEMPLATE_MOBILE_ORDER`= 11  WHERE `ZONE_TEMPLATE_ID`= 6268");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 12, `ZONE_TEMPLATE_MOBILE_ORDER`= 12  WHERE `ZONE_TEMPLATE_ID`= 6269");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 13, `ZONE_TEMPLATE_MOBILE_ORDER`= 13  WHERE `ZONE_TEMPLATE_ID`= 6077");


        //AGILE - Dealer Locator
        $this->addSql("UPDATE `psa_template_page` SET `TEMPLATE_PAGE_LABEL` = 'AGILE - NDP_TP_DEALOR_LOCATOR' WHERE `TEMPLATE_PAGE_ID` = 1518");
        $this->addSql("UPDATE `psa_template_page_area` SET  `TEMPLATE_PAGE_AREA_ORDER` = 5 , `LIGNE` = 5 WHERE `TEMPLATE_PAGE_ID`= 1518 AND `AREA_ID` = 122");
        $this->addSql("INSERT INTO `psa_template_page_area` VALUES ('1518', '150', '4', '4', '1', '4', '1', '0')");
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 5, `ZONE_TEMPLATE_MOBILE_ORDER`= 5  WHERE `ZONE_TEMPLATE_ID`= 6044");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('6272', 'NDP_PC5_UNE_COLONNE', '1518', '150', '776', '4', '4', NULL, NULL, '-2')");

        //NDP_TP_PLAN_DU_SITE
        $this->addSql("DELETE FROM `psa_zone_template`  WHERE `ZONE_TEMPLATE_ID` IN (4453, 4454)");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 3, `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID`= 4364");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 4, `ZONE_TEMPLATE_MOBILE_ORDER`= 4  WHERE `ZONE_TEMPLATE_ID`= 4363");

        //NDP_TP_MASTER_PAGE
        $this->addSql("DELETE FROM `psa_zone_template`  WHERE `ZONE_TEMPLATE_ID` IN (4447, 4448)");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 3, `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID`= 4349");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 4, `ZONE_TEMPLATE_MOBILE_ORDER`= 4  WHERE `ZONE_TEMPLATE_ID`= 4345");


        //NDP_TP_404
        $this->addSql("DELETE FROM `psa_zone_template`  WHERE `ZONE_TEMPLATE_ID` IN (4451, 4452)");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 3, `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID`= 4354");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 4, `ZONE_TEMPLATE_MOBILE_ORDER`= 4  WHERE `ZONE_TEMPLATE_ID`= 6118");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 5, `ZONE_TEMPLATE_MOBILE_ORDER`= 5  WHERE `ZONE_TEMPLATE_ID`= 4353");

        //NDP_TP_CONTACT
        $this->addSql("DELETE FROM `psa_zone_template`  WHERE `ZONE_TEMPLATE_ID` IN (4902, 4903, 4905, 4906, 4909)");
        $this->addSql("DELETE FROM  `psa_template_page_area` WHERE  `TEMPLATE_PAGE_ID`= 1000 AND `AREA_ID` = 148");
        $this->addSql("UPDATE `psa_template_page_area` SET  `TEMPLATE_PAGE_AREA_ORDER` = 3 , `LIGNE` = 3 WHERE `TEMPLATE_PAGE_ID`= 1000 AND `AREA_ID` = 150");
        $this->addSql("UPDATE `psa_template_page_area` SET  `TEMPLATE_PAGE_AREA_ORDER` = 4 , `LIGNE` = 4 WHERE `TEMPLATE_PAGE_ID`= 1000 AND `AREA_ID` = 122");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 2, `ZONE_TEMPLATE_MOBILE_ORDER`= 2  WHERE `ZONE_TEMPLATE_ID`= 4904");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 3, `ZONE_TEMPLATE_MOBILE_ORDER`= 3  WHERE `ZONE_TEMPLATE_ID`= 4907");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 4, `ZONE_TEMPLATE_MOBILE_ORDER`= 4  WHERE `ZONE_TEMPLATE_ID`= 4908");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 5, `ZONE_TEMPLATE_MOBILE_ORDER`= 5  WHERE `ZONE_TEMPLATE_ID`= 4910");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 7, `ZONE_TEMPLATE_MOBILE_ORDER`= 7  WHERE `ZONE_TEMPLATE_ID`= 4912");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('6273', 'NDP_PC5_UNE_COLONNE', '1000', '150', '776', '6', '6', NULL, NULL, '-2')");


        //AGILE - NDP_Blanc
        $this->addSql("DELETE FROM `psa_zone_template`  WHERE `ZONE_TEMPLATE_ID` = 6189");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 25, `ZONE_TEMPLATE_MOBILE_ORDER`= 25  WHERE `ZONE_TEMPLATE_ID`= 6190");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 26, `ZONE_TEMPLATE_MOBILE_ORDER`= 26  WHERE `ZONE_TEMPLATE_ID`= 6191");


        //Traduction des noms de tranches Release 1
        $tranches = array('NDP_PT21_NAVIGATION'=>'PT21 - Main navigation', 'NDP_PN7_ENTETE'=>'PN07 - Header', 'NPD_PT2_FOOTER'=>'PT02 - Footer', 'NDP_PN14_CONFISHOW_NAVIGATION'=>'PN14 - Showroom navigation', 'NDP_PN15_CONFISHOW_HEADER'=>'PN15 - Showroom header', 'NDP_PN13_ANCRES'=>'PN13 - Anchors', 'NDP_PF17_FORM'=>'PF17 - Forms', 'NDP_PF11_RECHERCHE_POINT_DE_VENTE'=>'PF11 - Dealer locator', 'NDP_PC40_CTA'=>'PC40 - Call To Action bloc', 'NDP_PC59_TOOLS'=>'PC59 - Tools', 'NDP_PF23_RANGE_BAR'=>'PF23 - Range bar', 'NDP_PF25_FILTRES_RESULTATS_CAR_SELECTOR'=>'PF25 - Car selector', 'NDP_PF27_Car_Picker'=>'PF27 - Car piker', 'NDP_PF2_PRESENTATION_SHOWROOM'=>'PF02 - Showroom presentation', 'NDP_PC77_DIMENSION_VEHICULE'=>'PC77 - Slider with thumbs', 'NDP_PC60_SUMMARY_AND_SHOWROOM_CTA'=>'PC60 - Showroom Call To Action', 'NDP_PC19_SLIDESHOW'=>'PC19 - Slideshow', 'NDP_PC23_MUR_MEDIA'=>'PC23 - Full mosaic gallery', 'NDP_PC79_LIGHT_MEDIA_WALL'=>'PC79 - Simple mosaic gallery', 'NDP_PF6_DRAG_DROP'=>'PF06 - Image comparator', 'NDP_PC5_UNE_COLONNE'=>'PC05 - Full width media', 'NDP_PC7_DEUX_COLONNES'=>'PC07 - 2 columns contents', 'NDP_PC9_UN_ARTICLE_UN_VISUEL'=>'PC09 - Aligned image content', 'NDP_PC12_3_COLONNES'=>'PC12 - 3 columns contents', 'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS'=>'PC68 - Content with 2 or 3 images', 'NDP_PC69_DEUX_COLONNES'=>'PC69 - 1/3  -3/4 contents', 'NDP_PT15_PLAN_DU_SITE'=>'PT15 - Sitemap', 'NDP_PN18_IFRAME'=>'PN18 - iFrame', 'NDP_PT20_MASTER_PAGE'=>'PT20 - Master page');
        foreach ($tranches as $key => $value){
            $this->replaceTranslations(
                array(
                    $key => array(
                        'expression' => $value,
                        'bo' => 1,
                        'LANGUE_ID' => '1'
                    ),
                )
            );
        }

        //MAJ Gabarits où le label de tranche est erroné
        $this->addSql("UPDATE `psa_zone_template` z
                        JOIN `psa_zone` a ON a.ZONE_ID  = z.ZONE_ID
                        SET z.ZONE_TEMPLATE_LABEL = a.ZONE_LABEL
                        where z.ZONE_TEMPLATE_LABEL NOT IN  (select ZONE_LABEL from psa_zone)");


        //CLEAN résiduts du gabarit blanc

        $tablesToUpdate = array('psa_page_zone','psa_page_zone_content','psa_page_zone_cta','psa_page_zone_cta_cta','psa_page_zone_media','psa_page_zone_multi','psa_page_zone_multi_cta','psa_page_zone_multi_cta_cta','psa_page_zone_multi_multi','psa_page_zone_vehicule','psa_user_page_zone','psa_user_zone_template');
        //Nouveau gabarit => Ancien Gabarit
        $listGabarits = array("1535"=>"290");

        foreach ($listGabarits as $gabaritCible => $gabaritInitial) {
            foreach($tablesToUpdate as $table){
                $this->addSql("UPDATE `$table` z
                                JOIN psa_zone_template a ON a.ZONE_TEMPLATE_ID  = z.ZONE_TEMPLATE_ID
                                JOIN psa_zone_template b on b.ZONE_TEMPLATE_LABEL LIKE concat('%',a.ZONE_TEMPLATE_LABEL,'%') AND b.TEMPLATE_PAGE_ID in ($gabaritCible)
                            SET z.ZONE_TEMPLATE_ID = b.ZONE_TEMPLATE_ID
                            where a.TEMPLATE_PAGE_ID in ($gabaritInitial)");
            }

            //UPDATE TEMPLATE_PAGE_ID
            $this->addSql("UPDATE psa_page_version SET `TEMPLATE_PAGE_ID` = $gabaritCible WHERE `TEMPLATE_PAGE_ID` IN ($gabaritInitial)");
        }

        //DELETE gabarit blanc initial
        $templatesTables = array('psa_template_page','psa_template_page_area','psa_zone_template');
        $listGabaritsInitiaux = implode(', ', array_values($listGabarits));
        foreach ($templatesTables as $table ) {
            $this->addSql("DELETE FROM `$table` WHERE `TEMPLATE_PAGE_ID` IN ($listGabaritsInitiaux)");

        }

        //CLEAN UPDATED TABLES
        foreach ($tablesToUpdate as $table ) {
            $this->addSql("DELETE FROM `$table` WHERE `ZONE_TEMPLATE_ID` NOT IN (
                            SELECT `ZONE_TEMPLATE_ID` FROM `psa_zone_template`)");
        }

        $this->addSql("set foreign_key_checks=1");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //AGILE - NDP_TP_SHOWROOM
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 24, `ZONE_TEMPLATE_MOBILE_ORDER`= 24  WHERE `ZONE_TEMPLATE_ID`= 6146");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('6144', 'NDP_PC83_ACCESSORIES_CONTENT', '1533', '150', '820', '23', '23', NULL, NULL, '-2')");

        //AGILE - NDP_TP_TECHNO
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 16, `ZONE_TEMPLATE_MOBILE_ORDER`= 16  WHERE `ZONE_TEMPLATE_ID`= 6262");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('6255', 'NDP_PC23_MUR_MEDIA', '1539', '150', '802', '15', '15', NULL, NULL, '-2')");

        //AGILE - Home page
        $this->addSql("UPDATE `psa_zone_template` SET  `ZONE_TEMPLATE_ORDER`= 15, `ZONE_TEMPLATE_MOBILE_ORDER`= 15  WHERE `ZONE_TEMPLATE_ID`= 6077");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('6073', 'NDP_PC12_3_COLONNES', '1530', '150', '760', '13', '13', NULL, NULL, '-2')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('6072', 'NDP_PC9_UN_ARTICLE_UN_VISUEL', '1530', '150', '752', '14', '14', NULL, NULL, '-2')");

        //AGILE - Dealer Locator
        $this->addSql("DELETE FROM  `psa_zone_template` WHERE  `ZONE_TEMPLATE_ID`= 6272");
        $this->addSql("DELETE FROM  `psa_template_page_area` WHERE  `TEMPLATE_PAGE_ID`= 1518 AND `AREA_ID` = 150");
        $this->addSql("UPDATE `psa_template_page_area` SET  `TEMPLATE_PAGE_AREA_ORDER` = 4 , `LIGNE` = 4 WHERE `TEMPLATE_PAGE_ID`= 1518 AND `AREA_ID` = 122");

        //NDP_TP_PLAN_DU_SITE
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 5, `ZONE_TEMPLATE_MOBILE_ORDER`= 5  WHERE `ZONE_TEMPLATE_ID`= 4364");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 6, `ZONE_TEMPLATE_MOBILE_ORDER`= 6  WHERE `ZONE_TEMPLATE_ID`= 4363");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4453', 'NDP_PT22_MY_PEUGEOT', '367', '121', '826', '4', '4', NULL, NULL, '-2')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4454', 'NDP_PT3_JE_VEUX', '367', '121', '801', '3', '3', NULL, NULL, '-2')");

        //NDP_TP_MASTER_PAGE
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 5, `ZONE_TEMPLATE_MOBILE_ORDER`= 5  WHERE `ZONE_TEMPLATE_ID`= 4349");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 6, `ZONE_TEMPLATE_MOBILE_ORDER`= 6  WHERE `ZONE_TEMPLATE_ID`= 4345");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4447', 'NDP_PT22_MY_PEUGEOT', '366', '121', '826', '3', '3', NULL, NULL, '-2')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4448', 'NDP_PT3_JE_VEUX', '366', '121', '801', '4', '4', NULL, NULL, '-2')");

        //NDP_TP_CONTACT
        $this->addSql("UPDATE `psa_template_page_area` SET  `TEMPLATE_PAGE_AREA_ORDER` = 4 , `LIGNE` = 4 WHERE `TEMPLATE_PAGE_ID`= 1000 AND `AREA_ID` = 150");
        $this->addSql("UPDATE `psa_template_page_area` SET  `TEMPLATE_PAGE_AREA_ORDER` = 5 , `LIGNE` = 5 WHERE `TEMPLATE_PAGE_ID`= 1000 AND `AREA_ID` = 122");
        $this->addSql("INSERT INTO `psa_template_page_area` VALUES ('1000', '148', '3', '3', '1', '4', '1', '0')");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 4, `ZONE_TEMPLATE_MOBILE_ORDER`= 4  WHERE `ZONE_TEMPLATE_ID`= 4904");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 7, `ZONE_TEMPLATE_MOBILE_ORDER`= 7  WHERE `ZONE_TEMPLATE_ID`= 4907");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 8, `ZONE_TEMPLATE_MOBILE_ORDER`= 8  WHERE `ZONE_TEMPLATE_ID`= 4908");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 10, `ZONE_TEMPLATE_MOBILE_ORDER`= 10  WHERE `ZONE_TEMPLATE_ID`= 4910");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 12, `ZONE_TEMPLATE_MOBILE_ORDER`= 12  WHERE `ZONE_TEMPLATE_ID`= 4912");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4902', 'NDP_PT22_MY_PEUGEOT', '1000', '121', '826', '2', '2', NULL, NULL, '-2')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4903', 'NDP_PT3_JE_VEUX', '1000', '121', '801', '3', '3', NULL, NULL, '-2')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4905', 'NDP_PF16_AUTRES_RESEAUX_SOCIAUX', '1000', '148', '762', '5', '5', NULL, NULL, '-2')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4909', 'NDP_PN3_TOGGLE_ACCORDEON', '1000', '150', '790', '9', '9', NULL, NULL, '-2')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4906', 'NDP_PN2_ONGLET', '1000', '148', '789', '6', '6', NULL, NULL, '-2')");

        //NDP_TP_404
        $this->addSql("DELETE FROM  `psa_zone_template` WHERE  `ZONE_TEMPLATE_ID`= 6273");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 5, `ZONE_TEMPLATE_MOBILE_ORDER`= 5  WHERE `ZONE_TEMPLATE_ID`= 4349");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4451', 'NDP_PT22_MY_PEUGEOT', '362', '121', '826', '3', '3', NULL, NULL, '-2')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('4452', 'NDP_PT3_JE_VEUX', '362', '121', '801', '4', '4', NULL, NULL, '-2')");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 5, `ZONE_TEMPLATE_MOBILE_ORDER`= 5  WHERE `ZONE_TEMPLATE_ID`= 4354");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 6, `ZONE_TEMPLATE_MOBILE_ORDER`= 6  WHERE `ZONE_TEMPLATE_ID`= 6118");
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 7, `ZONE_TEMPLATE_MOBILE_ORDER`= 7  WHERE `ZONE_TEMPLATE_ID`= 4353");

        //AGILE - NDP_Blanc
        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_TEMPLATE_ORDER`= 27, `ZONE_TEMPLATE_MOBILE_ORDER`= 27  WHERE `ZONE_TEMPLATE_ID`= 6191");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES ('6189', 'NDP_PC83_ACCESSORIES_CONTENT', '1535', '150', '820', '26', '26', NULL, NULL, '-2')");

    }
}
