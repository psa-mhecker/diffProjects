<?php

/**
 * Classe d'administration du contenu FAQ.
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 20/08/2013
 */
class Cms_Content_Citroen_Faq extends Cms_Content_Module
{
    /* Décache lors de l'action d'enregistrement ou de publication */
    public static $decacheBack = array(
        array('Frontend/Citroen/Faq/RubriqueContent',
          array(
                'SITE_ID',
                'LANGUE_ID',
              ),
        ),
        );
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {

        /* Initialisation des variables */
        $sControllerForm = '';

        $oConnection = Pelican_Db::getInstance();
//        /* Récupération des informations de Content_zone pour le PUSH_MEDIA */
//        $aContentZone = Backoffice_Form_Helper::getContentZoneValues($oController, 'PUSH_MEDIA');

        /* Question les plus posées */
        if (!empty($oController->values['CONTENT_SUBTITLE2'])) {
            $oController->values['CHECKBOX_MOST_WANTED_QUESTIONS'][] = 1;
        }
        $sControllerForm .= $oController->oForm->createCheckBoxFromList('CONTENT_SUBTITLE2', t('FORM_MOST_WANTED_QUESTIONS'), array(1 => t('YES')), $oController->values['CHECKBOX_MOST_WANTED_QUESTIONS'], false, $oController->readO);

        /* Rubrique FAQ associée */
        /* Création de la requête Select générique */
        $sSql = <<<SQL
                SELECT
                    FAQ_RUBRIQUE_ID,
                    FAQ_RUBRIQUE_LABEL
                FROM
                    #pref#_faq_rubrique
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                ORDER BY FAQ_RUBRIQUE_LABEL
SQL;
        /* Initialisation du Bind des variables */
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        /* Création du tableau de toutes les catégories */
        $aAllValues = $oConnection->queryTab($sSql, $aBind);

        /* Création de la liste déroulante des catégories de FAQ */
        $sComboFieldLabel = t('FORM_FAQ_RUB');
        $sHtmlComboField = <<<HTML
                        <tr>
                            <td class="formlib">{$sComboFieldLabel} *</td>
                            <td class="formval" >

HTML;
        $sHtmlComboField .= $oController->oForm->createComboFromList(
                'CONTENT_TITLE13',
                t('FORM_FAQ_RUB'),
                Backoffice_Form_Helper::getQueryTabKeyValue($aAllValues, 'FAQ_RUBRIQUE_ID', 'FAQ_RUBRIQUE_LABEL'),
                $oController->values['CONTENT_TITLE13'],
                true,
                $oController->readO,
                '1',
                false,
                '',
                true,
                true);
        /* Création de l'appel à la fonction d'ordre des contenus au sein de la rubrique auquel il est affecté */
        if (is_array($oController->values) && !empty($oController->values) && !empty($oController->values['CONTENT_CODE3'])) {
            $sJsPopupSort = <<<JS
                popupSimpleNoScroll('/_/Popup/sortContentFaq?sid={$_SESSION[APP]['SITE_ID']}&lid={$_SESSION[APP]['LANGUE_ID']}&rid={$oController->values['CONTENT_CODE3']}&uid={$oController->values['CONTENT_TYPE_ID']}&cid={$oController->values['CONTENT_ID']}', 'tri', 500, 500);
JS;
            $sHtmlComboField .= Pelican_Html::img(array(onclick => $sJsPopupSort, src => '/library/public/images/sort.gif', border => 0, alt => t('FORM_DISPLAY_ORDER'), width => 17, height => 18, align => 'center', hspace => 5, style => 'cursor:pointer;'));
        }
        $sHtmlComboField .= <<<HTML
                            </td>
                       </tr>

HTML;
        $sControllerForm .= $oController->oForm->createFreeHtml($sHtmlComboField);

        /* Gestion des mode d'affichage Web ou Mobile */
        $sControllerForm .= Backoffice_Form_Helper::getContentFormAffichage($oController, true, true);
        /* Libellé de la réponse */
        $sControllerForm .= $oController->oForm->createEditor('CONTENT_TEXT', t('FORM_RESPONSE'), true, $oController->values['CONTENT_TEXT'], $oController->readO, true);

        /* Lien CTA */
        $aOpenModes = Pelican::$config['TRANCHE_COL']['BLANK_SELF'];
        $sControllerForm .= $oController->oForm->createInput('CONTENT_TITLE2', t('FORM_LINK_LABEL'), 255, '', false, $oController->values['CONTENT_TITLE2'], $oController->readO, 100);
        $sControllerForm .= $oController->oForm->createInput('CONTENT_URL2', t('FORM_LINK_URL'), 255, 'internallink', false, $oController->values['CONTENT_URL2'], $oController->readO, 100);
        $sControllerForm .= $oController->oForm->createComboFromList('CONTENT_SUBTITLE', t('MODE_OUVERTURE'), $aOpenModes, $oController->values['CONTENT_SUBTITLE'], false, $oController->readO);
        $sControllerForm .= Backoffice_Form_Helper::getPushMediaCommun($oController, true);
        /* Gestion des messages d'erreur à intégrer dans la partie JS supplémentaire */
       //$sCtaTitleBlankErrorMsg = str_replace("'", "\'", t('ALERT_CTA_TITLE'));
        $sCtaUrlBlankErrorMsg = str_replace("'", "\'", t('ALERT_CTA_URL'));
        $sCtaModeBlankErrorMsg = str_replace("'", "\'", t('ALERT_CTA_MODE_OPEN_LINK'));

        /* Ajout du contrôle Javascript sur le remplissage du CTA et push media */
        $sJS = <<<JS
                // Récupération des valeurs des champs du CTA
                var ctaTitle        = $('#CONTENT_TITLE2').val();
                var ctaLink         = $('#CONTENT_URL2').val();
                var ctaOpenModeLink = $('#CONTENT_SUBTITLE').val();

                // Si au moins un des champs du CTA est rempli il faut qu'ils le soient tous
                if( !isBlank(ctaLink) || !isBlank(ctaOpenModeLink)){
                    // Si le libellé n'est pas contribué on remonte une erreur

                    // Si le l'URL n'est pas contribuée on remonte une erreur
                    if (isBlank(ctaLink)){
                        alert('{$sCtaUrlBlankErrorMsg}');
                        return false;
                    }
                    // Si le Mode d'ouverture n'est pas contribué on remonte une erreur
                    if (!ctaOpenModeLink){
                       alert('{$sCtaModeBlankErrorMsg}');
                       return false;
                    }
                }
JS;
        $sControllerForm .= $oController->oForm->createJS($sJS);

        return $sControllerForm;
    }
    /**
     * Méthode de surcharge de la sauvegarde.
     *
     * @param Pelican_Controller $oController
     */
    public static function save(Pelican_Controller $oController)
    {
        if (Pelican_Db::$values['CONTENT_CODE2'][0] == 1) {
            Pelican_Db::$values['CONTENT_CODE2'] = 1;
        } else {
            Pelican_Db::$values['CONTENT_CODE2'] = 0;
        }

        Backoffice_Form_Helper::saveContentFormAffichage();
        parent::save($oController);
        Backoffice_Form_Helper::saveContentZoneMultiValues('GALLERYFORM', 'GALLERYFORM');
        Pelican_Cache::clean('Frontend/Citroen/Faq/RubriqueContent');
        Pelican_Cache::clean('Frontend/Citroen/Faq/Rubrique');
    }
}
