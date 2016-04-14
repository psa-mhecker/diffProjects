<?php
/**
 * Tranche PF8 - Bloc Webstore Vehicule Neuf
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Yohann PHILIPPE <yohann.philippe@businessdecision.com>
 * @since 20/07/2015
 */

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

class Cms_Page_Ndp_Pf8WebstoreVehiculeNeuf extends Cms_Page_Ndp
{
	const PARCOURS_PDV = 1;
	const PARCOURS_REG = 2;
	const PARCOURS_PDT = 3;
	const MIN_LIMIT = 6;
	const YES = 0;
	const NO = 1;
	const MAX_PDV = 6;
	const PDV_DEFAUT = 6;
	const RAYON_DEFAUT = 20;

	public static function render(Pelican_Controller $controller)
	{
		$return = '';
		$aData = array(self::PARCOURS_PDV => t('NDP_PARCOURS_PDV'),
			self::PARCOURS_REG => t('NDP_PARCOURS_REGIONAL'),
			self::PARCOURS_PDT => t('NDP_PARCOURS_PRODUIT')
		);
		$aAutocompletion = array(
			self::YES => t('NDP_YES'),
			self::NO  => t('NDP_NO')
		);


		$return .= $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
		$infos = self::getRouteInfos($controller);
		$return .= $controller->oForm->createLabel(t('NDP_ROUTE_TYPE'), $aData[$infos]);

		$return .= $controller->oForm->createInput(
			$controller->multi.'ZONE_TITRE',
			t('TITLE'),
			120,
			'',
			false,
			$controller->zoneValues['ZONE_TITRE'],
			$controller->readO,
			75
		);

		if ($infos != self::PARCOURS_PDT) {
			self::setDefaultValueTo($controller->zoneValues, 'ZONE_LABEL', self::YES);
			$return .= $controller->oForm->createRadioFromList(
				$controller->multi.'ZONE_LABEL',
				t('AUTOCOMPLETION'),
				$aAutocompletion,
				$controller->zoneValues['ZONE_LABEL'],
				false,
				$controller->read0,
				'h'
			);
		}

		self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT2', self::MAX_PDV);
		$return .= $controller->oForm->createInput($controller->multi.'ZONE_ATTRIBUT2',
			t('NDP_NB_MAX_PDV'),
			1,
			'number',
			true,
			$controller->zoneValues['ZONE_ATTRIBUT2'],
			$controller->readO,
			3
		);

		self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT3', self::RAYON_DEFAUT);
		$return .= $controller->oForm->createInput($controller->multi.'ZONE_ATTRIBUT3',
			t('NDP_RADIUS'),
			2,
			'number',
			true,
			$controller->zoneValues['ZONE_ATTRIBUT3'],
			$controller->readO,
			3
		);
		
		$return .= self::addJSLimitPdv($controller);

		return $return;
	}
	/**
	 *
	 * @param Pelican_Controller $controller
	 */
	public static function addJSLimitPdv(Pelican_Controller $controller)
	{
		$js = "<script>
			  document.getElementById('" . $controller->multi."ZONE_ATTRIBUT2').onchange = function(){
				if (document.getElementById('" . $controller->multi."ZONE_ATTRIBUT2').value > ".self::PDV_DEFAUT.") {
					alert('" . t('NDP_NB_MAX_PDV')." : ".self::MAX_PDV."');
					document.getElementById('" . $controller->multi."ZONE_ATTRIBUT2').value = ".self::MAX_PDV.";
				}
			  }
			  </script>";
		return $js;
	}
	/**
	 *
	 * @param Pelican_Controller $controller
	 */
	public static function getRouteInfos(Pelican_Controller $controller) {
		self::$con = Pelican_Db::getInstance();
		$bind = [''];
		$sqlStr = 'SELECT ZONE_PARCOURS_WEBSTORE
				FROM #pref#_sites_et_webservices_psa
				WHERE SITE_ID=' . $_SESSION[APP][SITE_ID].'';
		$infos = self::$con->queryItem($sqlStr, $bind);

		return $infos;
	}


	/**
	 *
	 * @param Pelican_Controller $controller
	 */
	public static function save(Pelican_Controller $controller)
	{
		self::$con = Pelican_Db::getInstance();
		if (self::getRouteInfos($controller) == self::PARCOURS_PDT) {
			unset(Pelican_Db::$values['ZONE_LABEL']);
		}
		parent::save();
	}
}
