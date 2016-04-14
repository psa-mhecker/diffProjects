<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150706105210 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_zone` (`ZONE_ID`, `ZONE_TYPE_ID`, `ZONE_LABEL`, `ZONE_FREE`, `ZONE_COMMENT`, `ZONE_BO_PATH`, `ZONE_FO_PATH`, `ZONE_IFRAME`, `ZONE_AJAX`, `ZONE_PROGRAM`, `ZONE_DB_MULTI`, `ZONE_IMAGE`, `ZONE_CATEGORY_ID`, `ZONE_CONTENT`, `PLUGIN_ID`) VALUES
            (837, 1, 'NDP_PF17_FORM', 0, NULL, 'Cms_Page_Ndp_Pf17Form', NULL, 0, 0, 0, NULL, NULL, 28, 0, '')");
        $this->addSql("INSERT INTO `psa_zone_template` VALUES "
            ."(4495,'NDP_PF17_FORM',290,150,837,1,NULL, NULL,NULL,30)"
            .";"
        );        
        $this->addSql("INSERT INTO `psa_template` (`TEMPLATE_ID`, `TEMPLATE_TYPE_ID`, `TEMPLATE_GROUP_ID`, `TEMPLATE_LABEL`, `TEMPLATE_PATH`, `TEMPLATE_PATH_FO`, `TEMPLATE_COMPLEMENT`, `PLUGIN_ID`) VALUES
            (69, 3, 2, 'NDP_CNT_FORM', 'Cms_Content_Ndp_Pf17Form', NULL, NULL, '')");
        $this->addSql("INSERT INTO `psa_content_type` (`CONTENT_TYPE_ID`, `TEMPLATE_ID`, `CONTENT_TYPE_LABEL`, `CONTENT_TYPE_COMPLEMENT`, `CONTENT_TYPE_ADMINISTRATION`, `CONTENT_TYPE_PAGE`, `CONTENT_TYPE_DEFAULT`, `CONTENT_TYPE_PLUGIN`) VALUES
            (7, 69, 'NDP_CNT_FORM', NULL, 0, NULL, NULL, 0);");

        $this->addSql("INSERT INTO psa_content_type_site (CONTENT_TYPE_ID, SITE_ID, CONTENT_TYPE_SITE_EMISSION, CONTENT_TYPE_SITE_RECEPTION, CONTENT_ALERTE, CONTENT_ALERTE_URL) VALUES
            ('7', '2', NULL, NULL, NULL, NULL),
            ('7', '3', NULL, NULL, NULL, NULL)
        ");

        $this->addSql("INSERT INTO psa_user_role (USER_LOGIN, ROLE_ID, CONTENT_TYPE_ID, SITE_ID) VALUES
            ('admin', 7, 7, 2),
            ('admin', 7, 7, 3)
            ");
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_PF17_FORM",
                "NDP_CHOOSE_CONTENT",
                "NDP_NO_TYPE",
                "NDP_TYPE_PDV",
                "NDP_TYPE_CAR",
                "NDP_CODE_INSTANCE_FORM",
                "NDP_CHOOSE_TYPE_FORM",
                "NDP_FOR_CONFISHOW",
                "NDP_FOR_SHOWROOM",
                "NDP_CAR_LCDV16",
                "NDP_MOD_LCDV4",
                "NDP_GROUP_SILHOUETTE",
                "NDP_CODE_SITEGEO",
                "NDP_INTRODUCTION",
                "NDP_REQUEST_ACCEPTED",
                "NDP_CONFIRM_MAIL",
                "NDP_MORE_TEXT",
                "NDP_OTHER_ASK",
                "NDP_GENERIC_VISUAL",
                "NDP_MY_PEUGEOT",
                "NDP_DESC_MY_PEUGEOT",
                "NDP_MSG_EMAIL_FORM",
                "NDP_CTA_AND_LIEN",
                "NDP_PICTO_CTA",
                "NDP_ADD_FORM_CTA_AND_LIEN"
                )
            ');
        }
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_PF17_FORM', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CHOOSE_CONTENT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_NO_TYPE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_TYPE_PDV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_TYPE_CAR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CODE_INSTANCE_FORM', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CHOOSE_TYPE_FORM', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_FOR_CONFISHOW', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_FOR_SHOWROOM', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CAR_LCDV16', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MOD_LCDV4', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_GROUP_SILHOUETTE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CODE_SITEGEO', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_INTRODUCTION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REQUEST_ACCEPTED', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CONFIRM_MAIL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MORE_TEXT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ADD_FORM_CTA_AND_LIEN', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_OTHER_ASK', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MY_PEUGEOT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_GENERIC_VISUAL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_PICTO_CTA', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_EMAIL_FORM', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_DESC_MY_PEUGEOT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CTA_AND_LIEN', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_PF17_FORM', 1, 1, 'PF17 Contact Form template'),
            ('NDP_CHOOSE_CONTENT', 1, 1, 'Sélectionnez un contenu Formulaire'),
            ('NDP_NO_TYPE', 1, 1, 'Sans contextualisation'),
            ('NDP_TYPE_PDV', 1, 1, 'Contextualisé PDV'),
            ('NDP_TYPE_CAR', 1, 1, 'Contextualisé véhicule'),
            ('NDP_CODE_INSTANCE_FORM', 1, 1, 'Code instance du formulaire'),
            ('NDP_CHOOSE_TYPE_FORM', 1, 1, 'Sélection du type de formulaire'),
            ('NDP_FOR_CONFISHOW', 1, 1, 'Pour le Confishow ou configurateur'),
            ('NDP_FOR_SHOWROOM', 1, 1, 'Pour les showrooms'),
            ('NDP_CAR_LCDV16', 1, 1, 'Code véhicule LCDV16'),
            ('NDP_MOD_LCDV4', 1, 1, 'Code modèle LCDV4'),
            ('NDP_GROUP_SILHOUETTE', 1, 1, 'Code regroupement de silhouette'),
            ('NDP_CODE_SITEGEO', 1, 1, 'Code PDV IdSiteGeo'),
            ('NDP_INTRODUCTION', 1, 1, 'Introduction'),
            ('NDP_REQUEST_ACCEPTED', 1, 1, 'Prise en compte de la demande'),
            ('NDP_CONFIRM_MAIL', 1, 1, 'Confirmation de l\'e-mail'),
            ('NDP_MORE_TEXT', 1, 1, 'Texte supplémentaire'),
            ('NDP_OTHER_ASK', 1, 1, 'Accès aux autres demandes'),
            ('NDP_GENERIC_VISUAL', 1, 1, 'Visuel générique'),
            ('NDP_PICTO_CTA', 1, 1, 'Picto CTA'),
            ('NDP_MY_PEUGEOT', 1, 1, 'MyPeugeot'),
            ('NDP_DESC_MY_PEUGEOT', 1, 1, 'Descriptif MyPeugeot'),
            ('NDP_CTA_AND_LIEN', 1, 1, 'CTA / lien'),
            ('NDP_MSG_EMAIL_FORM', 1, 1, 'Pour récupérer l\'e-mail de l\'utilisateur, saisir le code suivant ##e-mail## à l\'endroit souhaité.'),
            ('NDP_ADD_FORM_CTA_AND_LIEN', 1, 1, 'Ajouter un CTA / lien')
           ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM psa_media_format WHERE MEDIA_FORMAT_LABEL = "Picto CTA" AND MEDIA_FORMAT_RATIO = 1');
        $this->addSql('DELETE FROM psa_user_role WHERE CONTENT_TYPE_ID=7');
        $this->addSql("DELETE FROM `psa_zone_template` WHERE ZONE_TEMPLATE_ID = 4495");
        $this->addSql("DELETE FROM `psa_zone` WHERE ZONE_ID = 837");
        $this->addSql("DELETE FROM `psa_content_type_site` WHERE CONTENT_TYPE_ID = 7");
        $this->addSql("DELETE FROM `psa_content_type` WHERE CONTENT_TYPE_ID = 7");
        $this->addSql("DELETE FROM `psa_template` WHERE TEMPLATE_ID = 69");
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_PF17_FORM",
                "NDP_CHOOSE_CONTENT",
                "NDP_NO_TYPE",
                "NDP_TYPE_PDV",
                "NDP_TYPE_CAR",
                "NDP_CODE_INSTANCE_FORM",
                "NDP_CHOOSE_TYPE_FORM",
                "NDP_FOR_CONFISHOW",
                "NDP_FOR_SHOWROOM",
                "NDP_CAR_LCDV16",
                "NDP_MOD_LCDV4",
                "NDP_GROUP_SILHOUETTE",
                "NDP_CODE_SITEGEO",
                "NDP_INTRODUCTION",
                "NDP_REQUEST_ACCEPTED",
                "NDP_CONFIRM_MAIL",
                "NDP_MORE_TEXT",
                "NDP_OTHER_ASK",
                "NDP_GENERIC_VISUAL",
                "NDP_MY_PEUGEOT",
                "NDP_DESC_MY_PEUGEOT",
                "NDP_MSG_EMAIL_FORM",
                "NDP_CTA_AND_LIEN",
                "NDP_PICTO_CTA",
                "NDP_ADD_FORM_CTA_AND_LIEN"
                )
            ');
        }
    }
}
