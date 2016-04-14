<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';

/**
 * Classe d'administration de la tranche Menu secondaire de Mon projet.
 */
class Cms_Page_Citroen_MonProjet_MenuSecondaire extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $return .= $oController->oForm->createLabel("", t('DECOUVRIR'));
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."ITEM_ACTIF", t('ACTIVATION_ITEM'), array(1 => ""), (isset($oController->zoneValues['ZONE_TITRE'])) ? 1 : 0, false, $oController->readO);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE", t('LIBELLE'), 255, "", false, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createLabel("", t('COMPARER'));
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."ITEM_ACTIF2", t('ACTIVATION_ITEM'), array(1 => ""), (isset($oController->zoneValues['ZONE_TITRE2'])) ? 1 : 0, false, $oController->readO);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE2", t('LIBELLE'), 255, "", false, $oController->zoneValues['ZONE_TITRE2'], $oController->readO, 75);
        $return .= $oController->oForm->createLabel("", t('ESSAYER'));
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."ITEM_ACTIF3", t('ACTIVATION_ITEM'), array(1 => ""), (isset($oController->zoneValues['ZONE_TITRE3'])) ? 1 : 0, false, $oController->readO);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE3", t('LIBELLE'), 255, "", false, $oController->zoneValues['ZONE_TITRE3'], $oController->readO, 75);
        $return .= $oController->oForm->createLabel("", t('TROUVER'));
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."ITEM_ACTIF4", t('ACTIVATION_ITEM'), array(1 => ""), (isset($oController->zoneValues['ZONE_TITRE4'])) ? 1 : 0, false, $oController->readO);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE4", t('LIBELLE'), 255, "", false, $oController->zoneValues['ZONE_TITRE4'], $oController->readO, 75);
        $return .= $oController->oForm->createLabel("", t('FINANCER'));
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."ITEM_ACTIF5", t('ACTIVATION_ITEM'), array(1 => ""), (isset($oController->zoneValues['ZONE_TITRE5'])) ? 1 : 0, false, $oController->readO);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE5", t('LIBELLE'), 255, "", false, $oController->zoneValues['ZONE_TITRE5'], $oController->readO, 75);
        $return .= $oController->oForm->createLabel("", t('PROFITER'));
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."ITEM_ACTIF6", t('ACTIVATION_ITEM'), array(1 => ""), (isset($oController->zoneValues['ZONE_TITRE6'])) ? 1 : 0, false, $oController->readO);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE6", t('LIBELLE'), 255, "", false, $oController->zoneValues['ZONE_TITRE6'], $oController->readO, 75);
        if (!$oController->readO) {
            $return .= $oController->oForm->createJS("
				if($('input[name=".$oController->multi."ITEM_ACTIF]').is(':checked')) {
					if ($('#".$oController->multi."ZONE_TITRE').val()=='') {
						alert('".t('LIBELLE_DECOUVRIR_OBLIGATOIRE', 'js2')."');
						return false;
					}
				}
				if($('input[name=".$oController->multi."ITEM_ACTIF2]').is(':checked')) {
					if ($('#".$oController->multi."ZONE_TITRE2').val()=='') {
						alert('".t('LIBELLE_COMPARER_OBLIGATOIRE', 'js2')."');
						return false;
					}
				}
				if($('input[name=".$oController->multi."ITEM_ACTIF3]').is(':checked')) {
					if ($('#".$oController->multi."ZONE_TITRE3').val()=='') {
						alert('".t('LIBELLE_ESSAYER_OBLIGATOIRE', 'js2')."');
						return false;
					}
				}
				if($('input[name=".$oController->multi."ITEM_ACTIF4]').is(':checked')) {
					if ($('#".$oController->multi."ZONE_TITRE4').val()=='') {
						alert('".t('LIBELLE_TROUVER_OBLIGATOIRE', 'js2')."');
						return false;
					}
				}
				if($('input[name=".$oController->multi."ITEM_ACTIF5]').is(':checked')) {
					if ($('#".$oController->multi."ZONE_TITRE5').val()=='') {
						alert('".t('LIBELLE_FINANCER_OBLIGATOIRE', 'js2')."');
						return false;
					}
				}
				if($('input[name=".$oController->multi."ITEM_ACTIF6]').is(':checked')) {
					if ($('#".$oController->multi."ZONE_TITRE6').val()=='') {
						alert('".t('LIBELLE_PROFITER_OBLIGATOIRE', 'js2')."');
						return false;
					}
				}
		   ");
        }

        return $return;
    }

    public static function save(Pelican_Controller $oController)
    {
        if (!Pelican_Db::$values['ITEM_ACTIF']) {
            unset(Pelican_Db::$values['ZONE_TITRE']);
        }
        if (!Pelican_Db::$values['ITEM_ACTIF2']) {
            unset(Pelican_Db::$values['ZONE_TITRE2']);
        }
        if (!Pelican_Db::$values['ITEM_ACTIF3']) {
            unset(Pelican_Db::$values['ZONE_TITRE3']);
        }
        if (!Pelican_Db::$values['ITEM_ACTIF4']) {
            unset(Pelican_Db::$values['ZONE_TITRE4']);
        }
        if (!Pelican_Db::$values['ITEM_ACTIF5']) {
            unset(Pelican_Db::$values['ZONE_TITRE5']);
        }
        if (!Pelican_Db::$values['ITEM_ACTIF6']) {
            unset(Pelican_Db::$values['ZONE_TITRE6']);
        }
        parent::save();
    }
}
