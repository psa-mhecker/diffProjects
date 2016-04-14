<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151019163942 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'PSA_INTEGRATION', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Intégration'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'CREER_UN_CONTENU_DE_TYPE_', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Créer un contenu de type : '");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'DISPLAYED_PUBLICATION_DATE', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Date de publication affichée'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'PSA_RECETTE', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Recette'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'PSA_PROD', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Production'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'PSA_PREPROD', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Pré-Production'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'DISPLAY_DATE_BEGIN', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Date de début d''affichage'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'la date de publication', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'La date de publication'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'les commentaires', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Les commentaires'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'les tags', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Les tags'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'les partages sociaux', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Les partages sociaux'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'le QR Code', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Le QR Code'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_ADD_SLIDE', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Ajouter un slide'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'WORKFLOW', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Contenus'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_MSG_CTA_USED', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Nombre de pages publiées utilisant le CTA'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'USED_COUNT', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Nombre d''utilisations'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'CHAPO', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Chapô'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_SLOGAN', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Phrase d''accroche'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_ACCESORIES_VISU', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Visuel accessoires'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_ACCESORIES_PARAMS', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Paramètres accessoires'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_REF_CENTRAL_ANGLE_VUE_MODEL_SILH', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Modèles/silhouettes'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_REF_MODELE_RGPMT_SILH', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Modèles/Regroupement de silhouettes'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_REF_MODELE_TOUS', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Tous les modèles'");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_SEGMENTATION_OF_FINITION', `LANGUE_ID` = 1, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Segment de la finition'");

        $this->addSql("REPLACE INTO `psa_label` SET `LABEL_ID` = 'NDP_SEGMENTATIONS_OF_FINITION', `LABEL_BACK` = 2, `LABEL_BO` = 1");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_SEGMENTATIONS_OF_FINITION', `LANGUE_ID` = 2, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Segment de finitions'");

        $this->addSql("REPLACE INTO `psa_label` SET `LABEL_ID` = 'NDP_MIG_SHOWROOM', `LABEL_BACK` = 2, `LABEL_BO` = 1");
        $this->addSql("REPLACE INTO `psa_label_langue_site` SET `LABEL_ID` = 'NDP_MIG_SHOWROOM', `LANGUE_ID` = 2, `SITE_ID` = 1, `LABEL_TRANSLATE` = 'Migration de données Showroom'");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
