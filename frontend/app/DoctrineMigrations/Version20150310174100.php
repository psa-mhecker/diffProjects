<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150310174100 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
(750, 1, 'NDP_PC2_Contenu_Texte_Riche', 0, NULL, 'Cms_Page_Ndp_Pc2ContenuTexteRiche', 'Pc2ContenuTexteRicheStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(751, 1, 'NDP_PC8_Contenu_2Colonnes_Texte', 0, NULL, 'Cms_Page_Ndp_Pc8Contenu2ColonnesTexte', 'Pc8Contenu2ColonnesTexteStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(752, 1, 'NDP_PC9_Contenu_1Article_1Visuel', 0, NULL, 'Cms_Page_Ndp_PC9Contenu1Article1Visuel', 'PC9Contenu1Article1VisuelStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(753, 1, 'NDP_PC18_Contenu_Grand_Visuel', 0, NULL, 'Cms_Page_Ndp_Pc18ContenuGrandVisuel', 'Pc18ContenuGrandVisuelStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(754, 1, 'NDP_PC19_Slideshow', 0, NULL, 'Cms_Page_Ndp_Pc19Slideshow', 'Pc19SlideshowStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(755, 1, 'NDP_PC38_Page404', 0, NULL, 'Cms_Page_Ndp_Pc38Page404', 'Pc38Page404Strategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(756, 1, 'NDP_PC93_SlideshowOffre', 0, NULL, 'Cms_Page_Ndp_Pc39SlideshowOffre', 'Pc39SlideshowOffreStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(757, 1, 'NDP_PC58_Contact', 0, NULL, 'Cms_Page_Ndp_Pc58Contact', 'Pc58ContactStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(758, 1, 'NDP_PC77_DimensionVehicule', 0, NULL, 'Cms_Page_Ndp_Pc77DimensionVehicule', 'Pc77DimensionVehiculeStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(759, 1, 'NDP_PF14_ReseauxSociaux', 0, NULL, 'Cms_Page_Ndp_Pf14ReseauxSociaux', 'Pf14ReseauxSociauxStrategy', 0, 0, 0, NULL, NULL, 28, 0, ''),
(760, 1, 'NDP_PC12_3Colonnes_Texte', 0, NULL, 'Cms_Page_Ndp_Pc123ColonnesTexte', 'Pc123ColonnesTexteStrategy', 0, 0, 0, NULL, NULL, 28, 0, '')");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_zone where ZONE_ID IN(750,751,752,753,754,755,756,757,758,759,760)");

    }
}
