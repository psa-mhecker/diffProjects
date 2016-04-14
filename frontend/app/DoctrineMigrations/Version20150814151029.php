<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150814151029 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_CNT_PROMOTION", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_HTML", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FLASH", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_PAGE_URL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SHORT_MAIN_PRICE_ADVANTAGE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_LONG_MAIN_PRICE_ADVANTAGE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SECOND_PRICE_ADVANTAGE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_OFFER_DESCRIPTION", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CAMPAGNE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_TOOLTIP_SQUARE_VISUAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SQUARE_VISUAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_HTML_CODE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_SWF_FILE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_XML_FILE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ALTERNATIVE_VISUAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ALTERNATIVE_TEXT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ALTERNATIVE_URL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_TOOLTIP_BIG_VISUAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BIG_VISUAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_TOOLTIP_MEGA_BANNER_VISUAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MEGA_BANNER_VISUAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MACAROON_VISUAL", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_TEXT_DESC_MENTIONS_LEGALES", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ADD_FORM_LINK", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ADD_FORM_CTA", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_NEED_SQUARE_VISUAL_OR_BIG_VISUAL", NULL, 2, NULL, NULL, 1, NULL)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_CNT_PROMOTION", 1, 1, "Contenu Promotion"),
                ("NDP_CNT_PROMOTION", 2, 1, "Promotion content"),
                ("NDP_HTML", 1, 1, "Html"),
                ("NDP_HTML", 2, 1, "Html"),
                ("NDP_FLASH", 1, 1, "Flash"),
                ("NDP_FLASH", 2, 1, "Flash"),

                ("NDP_PAGE_URL", 1, 1, "Url de la page"),
                ("NDP_PAGE_URL", 2, 1, "Url page"),

                ("NDP_SHORT_MAIN_PRICE_ADVANTAGE", 1, 1, "Avantage prix principal court"),
                ("NDP_SHORT_MAIN_PRICE_ADVANTAGE", 2, 1, "Short main price advantage"),

                ("NDP_LONG_MAIN_PRICE_ADVANTAGE", 1, 1, "Avantage prix principal long"),
                ("NDP_LONG_MAIN_PRICE_ADVANTAGE", 2, 1, "Long main price advantage"),

                ("NDP_SECOND_PRICE_ADVANTAGE", 1, 1, "Avantage prix secondaire"),
                ("NDP_SECOND_PRICE_ADVANTAGE", 2, 1, "Second price advantage"),

                ("NDP_OFFER_DESCRIPTION", 1, 1, "Descriptif de l’offre"),
                ("NDP_OFFER_DESCRIPTION", 2, 1, "Offer description"),

                ("NDP_CAMPAGNE", 1, 1, "Campagne"),
                ("NDP_CAMPAGNE", 2, 1, "Campagne"),

                ("NDP_MSG_TOOLTIP_SQUARE_VISUAL", 1, 1, "Utilisé dans les tranches Fiche Promotion, Slideshow offres carrées IAB et Campagne et Promotions associées"),
                ("NDP_MSG_TOOLTIP_SQUARE_VISUAL", 2, 1, "Used in blocs of Promotion Card, square Offer Slideshow IAB and Campagne and Promotions related "),

                ("NDP_SQUARE_VISUAL", 1, 1, "Visuel carré"),
                ("NDP_SQUARE_VISUAL", 2, 1, "Square visual"),

                ("NDP_HTML_CODE", 1, 1, "Code Html"),
                ("NDP_HTML_CODE", 2, 1, "Html code"),

                ("NDP_SWF_FILE", 1, 1, "Fichier SWF"),
                ("NDP_SWF_FILE", 2, 1, "SWF File"),

                ("NDP_XML_FILE", 1, 1, "Fichier XML"),
                ("NDP_XML_FILE", 2, 1, "XML File"),

                ("NDP_ALTERNATIVE_VISUAL", 1, 1, "Alternative visuel"),
                ("NDP_ALTERNATIVE_VISUAL", 2, 1, "Visual alternative"),

                ("NDP_ALTERNATIVE_TEXT", 1, 1, "Alternative textuelle"),
                ("NDP_ALTERNATIVE_TEXT", 2, 1, "Text alternative"),

                ("NDP_ALTERNATIVE_URL", 1, 1, "URL alternative"),
                ("NDP_ALTERNATIVE_URL", 2, 1, "Alternative URL"),

                ("NDP_MSG_TOOLTIP_BIG_VISUAL", 1, 1, "Veuillez redimensionner le visuel au ratio 1/3 pour l’affichage mobile.
 Utilisé dans les tranches Fiche Promotion et Slideshow Promotion"),
                ("NDP_MSG_TOOLTIP_BIG_VISUAL", 2, 1, "Please, resize the video at 1/3 ratio for the mobile display.
 Used in slices and Promotion Card Promotion Slideshow"),

                ("NDP_BIG_VISUAL", 1, 1, "Visuel Large"),
                ("NDP_BIG_VISUAL", 2, 1, "Big Visual"),

                ("NDP_MSG_TOOLTIP_MEGA_BANNER_VISUAL", 1, 1, "Utilisé dans la tranche Méga bannière offre IAB"),
                ("NDP_MSG_TOOLTIP_MEGA_BANNER_VISUAL", 2, 1, "Used in the slice Mega offers IAB banner"),

                ("NDP_MEGA_BANNER_VISUAL", 1, 1, "Visuel méga bannière"),
                ("NDP_MEGA_BANNER_VISUAL", 2, 1, "Mega Banner visual"),

                ("NDP_MACAROON_VISUAL", 1, 1, "Visuel macaron"),
                ("NDP_MACAROON_VISUAL", 2, 1, "Macaroon visual"),

                ("NDP_TEXT_DESC_MENTIONS_LEGALES", 1, 1, "Texte descriptif / mentions légales"),
                ("NDP_TEXT_DESC_MENTIONS_LEGALES", 2, 1, "Descriptive text / Legal Notice"),

                ("NDP_ADD_FORM_CTA", 1, 1, "Ajouter un CTA"),
                ("NDP_ADD_FORM_CTA", 2, 1, "Add CTA"),

                ("NDP_ADD_FORM_LINK", 1, 1, "Ajouter un lien"),
                ("NDP_ADD_FORM_LINK", 2, 1, "Add link"),

                ("NDP_NEED_SQUARE_VISUAL_OR_BIG_VISUAL", 1, 1, "Un des deux champs « Visuel carré » et « Visuel large » est obligatoire"),
                ("NDP_NEED_SQUARE_VISUAL_OR_BIG_VISUAL", 2, 1, "One of both fields « Square Visual » and « Big Visual » is required.")
            ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
             "NDP_CNT_PROMOTION",
                "NDP_HTML",
                "NDP_FLASH",
                "NDP_PAGE_URL",
                "NDP_SHORT_MAIN_PRICE_ADVANTAGE",
                "NDP_LONG_MAIN_PRICE_ADVANTAGE",
                "NDP_SECOND_PRICE_ADVANTAGE",
                "NDP_OFFER_DESCRIPTION",
                "NDP_CAMPAGNE",
                "NDP_MSG_TOOLTIP_SQUARE_VISUAL",
                "NDP_SQUARE_VISUAL",
                "NDP_HTML_CODE",
                "NDP_SWF_FILE",
                "NDP_XML_FILE",
                "NDP_ALTERNATIVE_VISUAL",
                "NDP_ALTERNATIVE_TEXT",
                "NDP_ALTERNATIVE_URL",
                "NDP_MSG_TOOLTIP_BIG_VISUAL",
                "NDP_BIG_VISUAL",
                "NDP_MSG_TOOLTIP_MEGA_BANNER_VISUAL",
                "NDP_MACAROON_VISUAL",
                "NDP_TEXT_DESC_MENTIONS_LEGALES",
                "NDP_ADD_FORM_LINK",
                "NDP_ADD_FORM_CTA",
                "NDP_NEED_SQUARE_VISUAL_OR_BIG_VISUAL"
                )
        ');
        }
    }
}
