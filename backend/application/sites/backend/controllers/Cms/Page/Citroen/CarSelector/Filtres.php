<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_CarSelector_Filtres extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return = Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE10", t('TITRE'), 255, "", true, $controller->zoneValues['ZONE_TITRE10'], $controller->readO, 75);

        $aTypeVehicule = Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR'];

        $aTypeVehiculeSelected1 = array();

        if ($controller->zoneValues['ZONE_TITRE11'] != '') {
            $aTypeVehiculeSelected1 = explode('##', $controller->zoneValues['ZONE_TITRE11']);
        } else {
            $aTypeVehiculeSelected1 = Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR_DEFAULT_1'];
        }

        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE11", t('CATEGORIE_VEHICULES'), $aTypeVehicule, $aTypeVehiculeSelected1, false, $controller->readO, "h", false);

        $return .= '<tr class="'.$controller->multi.'FILTRE_TEXTE_1 '.$controller->multi.'URL_OTHER_1 '.$controller->multi.'URL_OTHER_hide"><td class="formlib">'.t('OTHER_CAR_SELECTOR').' </td><td class="formval">'.$controller->oForm->createInput($controller->multi."ZONE_URL", t('FORM_LINK_URL'), 255, "internallink", false, $controller->zoneValues['ZONE_URL'], $readO, 100, true).'</td></tr>';

        $return .= '
        <script type="text/javascript">
       var elements = document.getElementsByName("'.$controller->multi.'ZONE_TITRE11");
       if(elements[2].checked)
       {
       	//on affiche le create url
    		$(".'.$controller->multi.'URL_OTHER_hide").hide();
       }
        $(document).ready(function () {
    	$(elements).change(function () {
    	if(elements[0].checked || elements[1].checked)
    	{
    		//on affiche le create url
    		$(".'.$controller->multi.'URL_OTHER_hide").show();
    	}
    	else
    	{
    		//on le cache

    		$(".'.$controller->multi.'URL_OTHER_hide").hide();
    	}
		});
		});

        </script>
        ';
        $aFiltreTypeSelected1 = array();
        if ($controller->zoneValues['ZONE_TITRE'] != '') {
            $aFiltreTypeSelected1 = explode('##', $controller->zoneValues['ZONE_TITRE']);
        }
        $aFiltreType1 = Pelican::$config['FILTRE_TYPE_1'];

        //Correction JIRA 2917
        /*if(empty($aFiltreTypeSelected1)){
            $aFiltreTypeSelected1 = Pelican::$config['FILTRE_TYPE_DEFAULT_1'];
        }*/
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE", t('FILTER_TYPE_1'), $aFiltreType1, $aFiltreTypeSelected1, false, $controller->readO, "h", false, "", t('FONCTIONNEMENT_FILTRE_TYPE'));

        $aFiltreType2 = Pelican::$config['FILTRE_TYPE_2'];
        $aFiltreTypeSelected2 = array();
        if ($controller->zoneValues['ZONE_TITRE2'] != '') {
            $aFiltreTypeSelected2 = explode('##', $controller->zoneValues['ZONE_TITRE2']);
        }
        //Correction JIRA 2917
        /*if(empty($aFiltreTypeSelected2)){
            $aFiltreTypeSelected2 = Pelican::$config['FILTRE_TYPE_DEFAULT_2'];
        }*/
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE2", t('FILTER_TYPE_2'), $aFiltreType2, $aFiltreTypeSelected2, false, $controller->readO, "h", false, "", t('FONCTIONNEMENT_FILTRE_TYPE'));

        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('VISUEL_CRIT_1'), true, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false, '16_9');
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID2", t('VISUEL_PRIX_COMPTANT'), true, "image", "", $controller->zoneValues["MEDIA_ID2"], $controller->readO, true, false, '16_9');
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID3", t('VISUEL_CRIT_2'), true, "image", "", $controller->zoneValues["MEDIA_ID3"], $controller->readO, true, false, '16_9');
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID4", t('VISUEL_CRIT_3'), true, "image", "", $controller->zoneValues["MEDIA_ID4"], $controller->readO, true, false, '16_9');

        $aFiltreMobile = Pelican::$config['FILTRE_MOBILE'];

        if (empty($aFiltreMobileSelected)) {
            $aFiltreMobileSelected = array(0,4);
        }
        if ($controller->zoneValues['ZONE_TITRE9'] != '') {
            $aFiltreMobileSelected = explode('##', $controller->zoneValues['ZONE_TITRE9']);
        }
        if (empty($aFiltreMobileSelected)) {
            $aFiltreMobileSelected = Pelican::$config['FILTRE_MOBILE_DEFAULT'];
        }

        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE9", t('FILTRE_MOBILE'), $aFiltreMobile, $aFiltreMobileSelected, false, $controller->readO, "h", false, "", t('FONCTIONNEMENT_FILTRE_TYPE'));

        $controller->zoneValues['ZONE_TITRE3'] = empty($controller->zoneValues['ZONE_TITRE3']) ? 1000 : $controller->zoneValues['ZONE_TITRE3'];
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('PAS_PRIX_COMPTANT'), 10, "number", true, $controller->zoneValues['ZONE_TITRE3'], $controller->readO, 20);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('MINI_PRIX_COMPTANT'), 10, "number", true, $controller->zoneValues['ZONE_TITRE4'], $controller->readO, 20);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE5", t('MAX_PRIX_COMPTANT'), 10, "number", true, $controller->zoneValues['ZONE_TITRE5'], $controller->readO, 20);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE6", t('SEUIL_PRIX_COMPTANT1'), 10, "number", true, $controller->zoneValues['ZONE_TITRE6'], $controller->readO, 20);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE7", t('SEUIL_PRIX_COMPTANT2'), 10, "number", true, $controller->zoneValues['ZONE_TITRE7'], $controller->readO, 20);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE8", t('SEUIL_PRIX_COMPTANT3'), 10, "number", true, $controller->zoneValues['ZONE_TITRE8'], $controller->readO, 20);
        $return .= $controller->oForm->createJS("
            var Seuil1 = $('#".$controller->multi."ZONE_TITRE6').val();
            var Seuil2 = $('#".$controller->multi."ZONE_TITRE7').val();
            var Seuil3 = $('#".$controller->multi."ZONE_TITRE8').val();

            if(!isNumeric(Seuil1)){
                alert('".t('ALERT_SEUIL_PRIX_1', 'js')."');
				fwFocus($('#".$controller->multi."ZONE_TITRE6'));
                return false;
            }
			if(!isNumeric(Seuil2)){
                alert('".t('ALERT_SEUIL_PRIX_2', 'js')."');
				fwFocus($('#".$controller->multi."ZONE_TITRE7'));
                return false;
            }
			if(!isNumeric(Seuil3)){
                alert('".t('ALERT_SEUIL_PRIX_3', 'js')."');
				fwFocus($('#".$controller->multi."ZONE_TITRE8'));
                return false;
            }
        ");

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        if (is_array(Pelican_Db::$values['ZONE_TITRE']) && count(Pelican_Db::$values['ZONE_TITRE'])>0) {
            Pelican_Db::$values['ZONE_TITRE'] = implode('##', Pelican_Db::$values['ZONE_TITRE']);
        }
        if (is_array(Pelican_Db::$values['ZONE_TITRE2']) && count(Pelican_Db::$values['ZONE_TITRE2'])>0) {
            Pelican_Db::$values['ZONE_TITRE2'] = implode('##', Pelican_Db::$values['ZONE_TITRE2']);
        }
        if (is_array(Pelican_Db::$values['ZONE_TITRE9']) && count(Pelican_Db::$values['ZONE_TITRE9'])>0) {
            Pelican_Db::$values['ZONE_TITRE9'] = implode('##', Pelican_Db::$values['ZONE_TITRE9']);
        }
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
