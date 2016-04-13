<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Actualites_Galerie extends Cms_Page_Citroen
{
	public static $decacheBack = array(
		array('Frontend/Citroen/Actualites/PageClearUrlByActu', 
            array('PAGE_ID','SITE_ID', 'LANGUE_ID') 
        ),
		array('Frontend/Citroen/Actualites/Liste', 
            array('PAGE_ID','SITE_ID', 'LANGUE_ID') 
        )
    );
	public static $decachePublication = array(
      	array('Frontend/Citroen/Actualites/PageClearUrlByActu', 
            array('PAGE_ID','SITE_ID', 'LANGUE_ID') 
        ),
		array('Frontend/Citroen/Actualites/Liste', 
            array('PAGE_ID','SITE_ID', 'LANGUE_ID') 
        )
    );
	public static function render(Pelican_Controller $controller)
    {
		$oConnection = Pelican_Db::getInstance ();
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
		$return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
		$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
		$aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $sSQL = '
			SELECT
				THEME_ACTUALITES_ID as "id",
				THEME_ACTUALITES_LABEL as "lib"
			FROM
				#pref#_theme_actualites
			where SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID
			ORDER BY THEME_ACTUALITES_ORDER
		';
		$aRes = $oConnection->queryTab($sSQL, $aBind);
		$aThemes = array();
		$aThemeSelected = array();
		if(is_array($aRes)  && count($aRes)>0){
			foreach($aRes as $res) {
				$aThemes[$res['id']] = $res['lib'];
			}
		}

		if($controller->zoneValues['ZONE_TITRE'] != ''){
			$aThemeSelected = explode('##',$controller->zoneValues['ZONE_TITRE']);
		}
		$return .= $controller->oForm->createCheckBoxFromList ( $controller->multi."ZONE_TITRE", t ( 'THEMES_ACTU' ), $aThemes, $aThemeSelected, true, $controller->readO, "h", false, "");
		$return .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($controller, 'ZONE_TITRE6', 'PUBLIC');
		$return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL_PAGE_FLUX'), 255, "internallink", true, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
		$aAffichageReseauxSociauxSelected = array();
		$aAffichageReseauxSociaux = array( 2 => "Twitter", 1 => "Facebook", 3 => "Youtube");
		if($controller->zoneValues['ZONE_TITRE5'] != ''){
			$aAffichageReseauxSociauxSelected = explode('##',$controller->zoneValues['ZONE_TITRE5']);
		}
		$return .= $controller->oForm->createCheckBoxFromList ( $controller->multi."ZONE_TITRE5", t ( 'RESEAUX_SOCIAUX' ), $aAffichageReseauxSociaux, $aAffichageReseauxSociauxSelected, false, $controller->readO, "h", false, "");
		$aListeReseauxSociaux = array();
		$aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE2", 'type' => "FACEBOOK");
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE3", 'type' => "YOUTUBE");
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE4", 'type' => "TWITTER");
        foreach ($aListeReseauxSociaux as $rs) {
            $return .= Backoffice_Form_Helper::getFormReseauSocial($controller, $rs['type'], $rs['champ']);
        }
		
		$return .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('URL_PAGE_YOUTUBE'), 255, "internallink", false, $controller->zoneValues['ZONE_URL2'], $controller->readO, 75);
		$return .= $controller->oForm->createJS("

		
			var theme = obj.elements['".$controller->multi."ZONE_TITRE[]'];
			var themeSelected = new Array();
			for (var i = 0; i < theme.length; i++){
				if (theme[i].checked) {
					themeSelected.push(theme[i].value);
				}
			}
			if(themeSelected.length < 2 || themeSelected.length > 5){
				alert('".t('ALERTE_THEME_ACTU', 'js2')."');
				return false;
			}
		");
		$return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createLabel(t('ABONNEMENT_NEWSLETTER'), "");
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE11", t('AFFICHER_ZONE_ABONNEMENT'), array(1 => ""), $controller->zoneValues['ZONE_TITRE11'], false, $controller->readO);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
		if(is_array(Pelican_Db::$values['ZONE_TITRE']) && count(Pelican_Db::$values['ZONE_TITRE'])>0){
			Pelican_Db::$values['ZONE_TITRE'] = implode('##',Pelican_Db::$values['ZONE_TITRE']);
		}
		if(is_array(Pelican_Db::$values['ZONE_TITRE5']) && count(Pelican_Db::$values['ZONE_TITRE5'])>0){
			Pelican_Db::$values['ZONE_TITRE5'] = implode('##',Pelican_Db::$values['ZONE_TITRE5']);
		}
		Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
          foreach (self::$decachePublication as $keyCache => $valueCache) {
        	Pelican_Cache::clean($valueCache[0]);
        }
    }
	
}