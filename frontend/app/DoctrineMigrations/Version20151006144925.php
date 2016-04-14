<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151006144925 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('FORCED_FORMAT', null, 2, null, null, 1, null),
              ('NDP_MY_PEUGEOT', null, 2, null, null, 1, null)
        ");

        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('MEDIATHEQUE', 1, 1, 'Médiathèque'),
              ('FORCED_FORMAT', 1, 1, 'Format forcé'),
              ('POPUP_MEDIA_LABEL_LAST_ACCES', 1, 1, 'Date de création'),
              ('CREATEUR', 1, 1, 'Créateur'),
              ('LEGENDE', 1, 1, 'Légende (alt)'),
              ('POPUP_MEDIA_MSG_DEL_FILE', 1, 1, 'Êtes-vous sûr de vouloir supprimer ce fichier ?'),
              ('POPUP_MEDIA_MSG_DEL_FOLDER', 1, 1, 'Êtes-vous sûr de vouloir supprimer ce fichier ?'),
              ('WS_GEST_RANGE_MANAGER', 1, 1, 'Webservice Gestionnaire de la gamme'),
              ('WS_BO_FORMS', 1, 1, 'Webservice BO Forms'),
              ('NDP_REF_SITE_WS_PSA', 1, 1, 'Configuration des sites PSA'),
              ('NDP_MY_PEUGEOT', 1, 1, 'MyPeugeot'),
              ('TABLE_FILTER_ALL', 1, 1, 'Tous'),
              ('NDP_SLOGAN', 1, 1, 'Phrase d \'accroche'),
              ('NDP_FINISHING_ORDER', 1, 1, 'Ordre des finitions'),
              ('GLOBAL_PARAMETERS', 1, 1, 'Paramètres généraux'),
              ('NDP_NATIONAL_PARAMETERS', 1, 1, 'Paramètres nationaux'),
              ('CREER_UN_CONTENU_DE_TYPE_', 1, 1, 'Créer un type de contenu'),
              ('NDP_REF_RESEAUX_SOCIAUX_PARAM', 1, 1, 'Référentiel réseaux sociaux'),
              ('ACCES_BACK_OFFICE', 1, 1, 'Accès Back Office'),
              ('USER_IS_LDAP_ONLY_READ', 1, 1, 'Cet utilisateur est un utilisateur LDAP, il ne peut être modifié'),
              ('DROITS_ETENDUS', 1, 1, 'Droits étendus'),
              ('FORM_MSG_LIST_SELECTED', 1, 1, 'Valeurs sélectionnées'),
              ('CANT_BE_DELETED', 1, 1, '(ne peut être supprimé)'),
              ('GENERAL', 1, 1, 'Général'),
              ('GENERALE', 1, 1, 'Général'),
              ('GLOBAL_PAGE', 1, 1, '- Général -'),
              ('CHANGE_CLEAR_URL', 1, 1, 'Voulez-vous mettre à jour l\'URL claire ?'),
              ('CONFIRM_DELETE', 1, 1, 'Êtes-vous sûr de vouloir supprimer cette page ainsi que les pages et contenus rattachés ?')
        ");
        $this->addSql("UPDATE psa_page_version SET `PAGE_TITLE_BO`='- Général -' WHERE PAGE_TITLE_BO LIKE '%PAGE_GENERAL%'");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = REPLACE(LABEL_TRANSLATE, 'Url', 'URL')");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = REPLACE(LABEL_TRANSLATE, 'url', 'URL')");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = REPLACE(LABEL_TRANSLATE, 'html', 'HTML')");
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = REPLACE(LABEL_TRANSLATE, 'Html', 'HTML')");
        
        $this->addSql("UPDATE psa_template SET TEMPLATE_LABEL ='NDP_ACCESORIES_VISU' WHERE TEMPLATE_LABEL ='NDP_VISU_ACCESS'");
        $this->addSql("UPDATE psa_template SET TEMPLATE_LABEL ='NDP_ACCESORIES_PARAMS' WHERE TEMPLATE_LABEL ='NDP_PARAMS_ACCESS'");
        $this->addSql("UPDATE psa_template SET TEMPLATE_LABEL ='NDP_REF_APPLI_CONNECT_APPS' WHERE TEMPLATE_LABEL ='NDP_REF_APPLI_CONNECT_APP'");
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
                "FORCED_FORMAT"
                )
            ');
        }
    }
}
