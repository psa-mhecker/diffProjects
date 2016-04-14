<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150616172307 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        //Ajout des bonnes trads qui n'existent pas
         $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_PF2_PRESENTATION_SHOWROOM', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PC38_PAGE_404', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PC33_OFFRE_PLUS', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_PF2_PRESENTATION_SHOWROOM', 1, 1, 'Media slideshow header confishow _content'),
            ('NDP_PC38_PAGE_404', 1, 1, '404_ navigation'),
            ('NDP_PC33_OFFRE_PLUS', 1, 1, 'Slideshow_ratio cinemascope or 16/9 _ content')
           ");
        //corrections des clés
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC33_OFFRE_PLUS' WHERE ZONE_TEMPLATE_ID = 4181 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC33_OFFRE_PLUS' WHERE ZONE_TEMPLATE_ID = 4301 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC23_MUR_MEDIA' WHERE ZONE_TEMPLATE_ID = 4286 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC23_MUR_MEDIA' WHERE ZONE_TEMPLATE_ID = 4386 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC2_CONTENU_TEXTE_RICHE' WHERE ZONE_TEMPLATE_ID = 4391 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC2_CONTENU_TEXTE_RICHE' WHERE ZONE_TEMPLATE_ID = 4306 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC5_UNE_COLONNE' WHERE ZONE_TEMPLATE_ID = 4288 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC5_UNE_COLONNE' WHERE ZONE_TEMPLATE_ID = 4303 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC16_VERBATIM' WHERE ZONE_TEMPLATE_ID = 4380 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC18_CONTENU_GRAND_VISUEL' WHERE ZONE_TEMPLATE_ID = 4111 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC18_CONTENU_GRAND_VISUEL' WHERE ZONE_TEMPLATE_ID = 4337 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC18_CONTENU_GRAND_VISUEL' WHERE ZONE_TEMPLATE_ID = 4398 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC19_SLIDESHOW' WHERE ZONE_TEMPLATE_ID = 4292 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC36_FAQ' WHERE ZONE_TEMPLATE_ID = 4359 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC38_PAGE_404' WHERE ZONE_TEMPLATE_ID = 4118 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC38_PAGE_404' WHERE ZONE_TEMPLATE_ID = 4250 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC38_PAGE_404' WHERE ZONE_TEMPLATE_ID = 4354 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC39_SLIDESHOW_OFFRE' WHERE ZONE_TEMPLATE_ID = 4390 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC40_CTA' WHERE ZONE_TEMPLATE_ID = 4293 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC40_CTA' WHERE ZONE_TEMPLATE_ID = 4304 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC58_CONTACT' WHERE ZONE_TEMPLATE_ID = 4294 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC59_TOOLS' WHERE ZONE_TEMPLATE_ID = 4296 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS' WHERE ZONE_TEMPLATE_ID = 4335 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC9_UN_ARTICLE_UN_VISUEL' WHERE ZONE_TEMPLATE_ID = 4385 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PF6_DRAG_DROP' WHERE ZONE_TEMPLATE_ID = 4334 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PF14_RESEAUX_SOCIAUX' WHERE ZONE_TEMPLATE_ID = 4300 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PN7_ENTETE' WHERE ZONE_TEMPLATE_ID = 4343 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PN7_ENTETE' WHERE ZONE_TEMPLATE_ID = 4356 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PN7_ENTETE' WHERE ZONE_TEMPLATE_ID = 4361 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PN7_ENTETE' WHERE ZONE_TEMPLATE_ID = 4366 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT19_ENGAGEMENTS' WHERE ZONE_TEMPLATE_ID = 4295 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT19_ENGAGEMENTS' WHERE ZONE_TEMPLATE_ID = 4160 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT17_CHOIX_LANGUE' WHERE ZONE_TEMPLATE_ID = 4350 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT20_ADMIN' WHERE ZONE_TEMPLATE_ID = 4349 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT20_ADMIN' WHERE ZONE_TEMPLATE_ID = 4378 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT2_FOOTER' WHERE ZONE_TEMPLATE_ID = 4338 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT2_FOOTER' WHERE ZONE_TEMPLATE_ID = 4345 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT2_FOOTER' WHERE ZONE_TEMPLATE_ID = 4351 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT2_FOOTER' WHERE ZONE_TEMPLATE_ID = 4353 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT2_FOOTER' WHERE ZONE_TEMPLATE_ID = 4358 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT2_FOOTER' WHERE ZONE_TEMPLATE_ID = 4425 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT2_FOOTER' WHERE ZONE_TEMPLATE_ID = 4363 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT21_NAVIGATION' WHERE ZONE_TEMPLATE_ID = 4342 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT21_NAVIGATION' WHERE ZONE_TEMPLATE_ID = 4346 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT21_NAVIGATION' WHERE ZONE_TEMPLATE_ID = 4355 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT21_NAVIGATION' WHERE ZONE_TEMPLATE_ID = 4360 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT21_NAVIGATION' WHERE ZONE_TEMPLATE_ID = 4365 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT21_NAVIGATION' WHERE ZONE_TEMPLATE_ID = 4383 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT21_NAVIGATION' WHERE ZONE_TEMPLATE_ID = 4389 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT21_NAVIGATION' WHERE ZONE_TEMPLATE_ID = 4389 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC7_DEUX_COLONNES' WHERE ZONE_TEMPLATE_ID = 4336 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC39_SLIDESHOW_OFFRE' WHERE ZONE_TEMPLATE_ID = 4122 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PC39_SLIDESHOW_OFFRE' WHERE ZONE_TEMPLATE_ID = 4123 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT3_JE_VEUX' WHERE ZONE_TEMPLATE_ID = 4419 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT3_JE_VEUX' WHERE ZONE_TEMPLATE_ID = 4427 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT22_MY_PEUGEOT' WHERE ZONE_TEMPLATE_ID = 4418 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PT22_MY_PEUGEOT' WHERE ZONE_TEMPLATE_ID = 4426 ");
        $this->addSql("UPDATE psa_zone_template SET ZONE_TEMPLATE_LABEL = 'NDP_PN13_ANCRES' WHERE ZONE_TEMPLATE_ID = 4127 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PC68_UN_ARTICLE_DEUX_OU_TROIS_VISUELS' WHERE ZONE_ID = 766 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PC5_UNE_COLONNE' WHERE ZONE_ID = 776 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PF6_DRAG_DROP' WHERE ZONE_ID = 786 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PC7_DEUX_COLONNES' WHERE ZONE_ID = 781 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PC7_DEUX_COLONNES' WHERE ZONE_ID = 781 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PC69_DEUX_COLONNES' WHERE ZONE_ID = 767 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PC8_DEUX_COLONNES_TEXTE' WHERE ZONE_ID = 751 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PC9_UN_ARTICLE_UN_VISUEL' WHERE ZONE_ID = 752 ");
        $this->addSql("UPDATE psa_zone SET ZONE_LABEL = 'NDP_PF42_SELECTEUR_DE_TEINTE_360' WHERE ZONE_ID = 784 ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_PF2_PRESENTATION_SHOWROOM",
                "NDP_PC38_PAGE_404",
                "NDP_PC33_OFFRE_PLUS"
                )
            ');
        }
    }
}
