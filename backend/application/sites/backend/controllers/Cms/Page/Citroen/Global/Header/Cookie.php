<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche Cookie.
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 27/08/2013
 */
class Cms_Page_Citroen_Global_Header_Cookie extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $sControllerForm = '';

        /* Création du tableau des valeurs des boutons radio en incluant */
        $aRadioGestionMode = Pelican::$config['ACCEPT_COOKIES'];
        $aRadioGestionMode['FORCE_COOKIES'] =
                Pelican::$config['ACCEPT_COOKIES']['FORCE_COOKIES'].
                Pelican_Html::nbsp().
                Pelican_Html::span(
                        array(
                            'title' => 'Aucune info sur les cookies n’est donnée sur le site. Dans ce cas, les cookies sont acceptés par défaut et la page + tranche cookies doivent pouvoir être désactivés.',
                            'id' => "INFO_{$oController->multi}ZONE_PARAMETERS_0",
                            ), '(i)');
        $aRadioGestionMode['INFO_COOKIES'] =
                Pelican::$config['ACCEPT_COOKIES']['INFO_COOKIES'].
                Pelican_Html::nbsp().
                Pelican_Html::span(
                        array(
                            'title' => 'Par défaut, les cookies sont acceptés. La tranche « cookies » s’affiche avec un texte du type « En naviguant sur ce site vous acceptez les cookies… », un bouton « Continuez », une page « plus d’infos sur les cookies ». Si l’internaute continue à naviguer sur le site, la tranche « cookies » ne s’affiche plus.',
                            'id' => "INFO_{$oController->multi}ZONE_PARAMETERS_1",
                            ), '(i)');

        $aRadioGestionMode['ACCEPT_COOKIES'] =
                Pelican::$config['ACCEPT_COOKIES']['ACCEPT_COOKIES'].
                Pelican_Html::nbsp().
                Pelican_Html::span(
                        array(
                            'title' => 'Par défaut, aucun cookie (sauf exception) ne doit être déposé. La tranche « cookies » s’affiche avec un texte, le bouton « Acceptez » et une page « plus d’infos sur les cookies ». Si l’internaute continue à naviguer sur le site, la tranche « cookies » reste affichée. A l’acceptation, cette tranche disparait.',
                            'id' => "INFO_{$oController->multi}ZONE_PARAMETERS_2",
                            ), '(i)');

        /* Gestion du mode d'affichage des cookies */
        $sControllerForm .= $oController->oForm->createRadioFromList(
                $oController->multi.'ZONE_PARAMETERS',
                t('FORM_GESTION_MODE'),
                $aRadioGestionMode,
                $oController->zoneValues['ZONE_PARAMETERS'],
                true,
                $oController->readO);

        /* Activer la croix de fermeture */
        $sControllerForm .= $oController->oForm->createCheckBoxFromList(
                $oController->multi.'ZONE_ATTRIBUT',
                t('FORM_SHOW_CLOSE_BUTTON'),
                array(1 => ''),
                $oController->zoneValues['ZONE_ATTRIBUT'],
                false,
                $oController->readO);

        /* Texte */
        $sControllerForm .= $oController->oForm->createEditor(
               $oController->multi.'ZONE_TEXTE',
               t('TEXTE'),
               false,
               $oController->zoneValues['ZONE_TEXTE'],
               $oController->readO,
               true,
               "",
               650,
               150);
        /* Libellé du bouton d’accpetation */
        $sControllerForm .= $oController->oForm->createInput(
               $oController->multi.'ZONE_TITRE2',
               t('FORM_ACCEPT_BUTTON_LABEL'),
               255, '',
               false,
               $oController->zoneValues['ZONE_TITRE2'],
               $oController->readO,
               100);
        /* Libellé du lien */
        $sControllerForm .= $oController->oForm->createInput(
               $oController->multi.'ZONE_TITRE3',
               t('FORM_LINK_LABEL'),
               255, '',
               false,
               $oController->zoneValues['ZONE_TITRE3'],
               $oController->readO,
               100);
        /* URL du lien */
        $sControllerForm .= $oController->oForm->createInput(
               $oController->multi.'ZONE_URL',
               t('FORM_LINK_URL'),
               255,
               'internallink',
               false,
               $oController->zoneValues['ZONE_URL'],
               $oController->readO,
               100);

        /* Activation des infobulles pour les boutons radio "mode gestion" */
        /*$sControllerForm .=  <<<JS
                <script>
                    $(function() {
                        $( "form span" ).tooltip();
                    });
                </script>
JS;*/
        return $sControllerForm;
    }
}
