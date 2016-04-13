<?php

use Citroen\GammeFinition\VehiculeGamme;
use Citroen\Financement;
use Citroen\SelectionVehicule;

require_once(Pelican::$config['APPLICATION_CONTROLLERS'] . '/Layout/Citroen/Comparateur.php');
require_once(Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Zone.php');

/**
 * Classe d'affichage Front de la tranche Selection vehicules de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_SelectionVehicules_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		unset($_GET['select_vehicule']);
		unset($_GET['select_vehicule_lcdv6']);
		$oUser = \Citroen\UserProvider::getUser();
		$aParams = $this->getParams();

		$aVehiculesFromNavigation = Layout_Citroen_Comparateur_Controller::getVehiculeModeleFromNavigation();
		if ($oUser && $oUser->isLogged()) {
			$iUserId = $oUser->getId();
		} else {
			$iUserId = null;
		}
		$aVehiculeSelection = SelectionVehicule::getUserSelection($iUserId);

		$aSelectionDetails = array();
		if (is_array($aVehiculeSelection) && count($aVehiculeSelection)) {
			foreach ($aVehiculeSelection as $aSelection) {
				$aVehiculeInfo = self::getInfosVehicule(
						$aSelection['lcdv6_code'], $aSelection['finition_code'], $aSelection['version_code']
				);
				if (isset($aVehiculeInfo['VEHICULE_LABEL'])) {
					$aVehiculeInfo['LABEL'] = $aVehiculeInfo['VEHICULE_LABEL'];
					unset($arr['VEHICULE_LABEL']);
				}
				//$aVehiculeInfo['VEHICULE_ID'] = $aSelection['VEHICULE_ID'];
				$aSelectionDetails[$aSelection['ordre']] = $aVehiculeInfo;
			}
			if (isset($_SESSION[APP]['VEHICULE_SELECTION'])) {
				//if (isset($_GET['selected'])) {
				//$index = $_GET['selected'];
				$index = $_SESSION[APP]['VEHICULE_SELECTION'];
				if (!isset($aSelectionDetails[$index])) {
					$index = 0;
				}
			} else {
				$index = 0;
			}
			$_GET['select_vehicule'] = $aSelectionDetails[$index]['VEHICULE_ID'];
			$_GET['select_vehicule_lcdv6'] = $aSelectionDetails[$index]['LCDV6'];
			if ($aSelectionDetails[$index]['FINITION_CODE']) {
				$_GET['select_vehicule_finition'] = $aSelectionDetails[$index]['FINITION_CODE'];
			} elseif ($aSelectionDetails[$index]['GR_COMMERCIAL_NAME_CODE']) {
				$_GET['select_vehicule_finition'] = $aSelectionDetails[$index]['GR_COMMERCIAL_NAME_CODE'];
			}
		}

		$this->assign('aSelection', $aVehiculeSelection);
		$this->assign('aSelectionDetails', $aSelectionDetails);
		$this->assign('aVehicules', $aVehiculesFromNavigation);

		if ($_SESSION[APP]['VEHICULE_SELECTION']) {
			$iSelectionId = $index;
		}
		if ($_SESSION[APP]['VEHICULE_SELECTION_EDITION']) {
			$iEditionId = $index;
		}

		$this->assign('oUser', $oUser);
		$this->assign('iSelectionId', $iSelectionId);
		$this->assign('iEditionId', $iEditionId);
		$this->assign('aParams', $aParams);
		$this->assign('sIncludeTplPath', Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Citroen/MonProjet/SelectionVehicules/');

		$this->fetch();
	}

	/**
	 * methode dummy pour tracer la reconsultation de mon projet
	 */
	public function onMyProjectAjaxAction()
	{
		$this->addResponseCommand('debug', array(1));
	}

	public function getFinitionsByGammeAjaxAction()
	{
		$aParams = $this->getParams();
		$aFinitions = Pelican_Cache::fetch("Frontend/Citroen/SimulateurFinancement/Finitions", array(
				$aParams['v'], //LCDV6
				Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'],
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
		));

		// Récupération des informations su le véhicule
		$aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($_GET['v'], Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);

		$this->assign('finitionsSelect', $aFinitions);
		$this->assign('sLCDV6', $aParams['v']);
		$this->assign('finitionCurrent', $aParams['f']);
		$this->assign('aVehicule', $aVehicule);
		$this->fetch();
	}

	public function getEnginesByFinitionAjaxAction()
	{
		$aParams = $this->getParams();
		$urlArgs = explode('|', $aParams['v']);
		$sLcdv6 = $urlArgs[0];
		$sFinition = null;
		$sVersion = null;
		if (isset($urlArgs[1])) {
			$sFinition = $urlArgs[1];
		}
		if (isset($urlArgs[2])) {
			$sVersion = $urlArgs[2];
		}

		$aEngineList = VehiculeGamme::getEngineList(
				$sFinition, $sLcdv6, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']
		);

		// Récupération des informations su le véhicule
		$aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($_GET['v'], Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);

		$this->assign('enginesSelect', $aEngineList);
		$this->assign('sFinitionCode', $aParams['v']);
		$this->assign('engineCurrent', $aParams['e']);
		$this->assign('aVehicule', $aVehicule);
		$this->fetch();
	}

	public function changeOrderAjaxAction()
	{
		$aParams = $this->getParams();
		$oUser = \Citroen\UserProvider::getUser();
		if ($oUser && $oUser->isLogged()) {
			$user_id = $oUser->getId();
		} else {
			$user_id = null;
		}
		$aItemLines = explode(',', $aParams['items']);
		$i = 0;
		foreach ($aItemLines as $sItemLine) {
			$aItems = explode('|', $sItemLine);
			SelectionVehicule::addToSelection(
				$user_id, $i, $aItems[0], $aItems[1], $aItems[2]
			);
			$i++;
		}
		$this->addResponseCommand('debug', array(1));
	}

	public function getVehiculeImagePrixAjaxAction()
	{
		$aParams = $this->getParams();
		$urlArgs = explode('|', $aParams['v']);
		$sLcdv6 = $urlArgs[0];
		(isset($urlArgs[1])) ? $sFinition = $urlArgs[1] : $sFinition = null;
		(isset($urlArgs[2])) ? $sVersion = $urlArgs[2] : $sVersion = null;
		$aData = self::getInfosVehicule($sLcdv6, $sFinition, $sVersion);
		$this->assign('vehiculeImage', $aData['IMAGE']);
		$this->assign('i', $aParams['order']);
		$this->fetch();
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'sv_car' . $aParams['order'],
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
	}

	public static function getInfosVehicule($sLcdv6, $sFinition = null, $sVersion = null)
	{
		if ($sLcdv6 != null && $sFinition == null & $sVersion == null) {
			//fetch image from vehicule media table
			$aData = Pelican_Cache::fetch("Frontend/Citroen/MonProjet/InfosVehiculeByLcdv6", array(
					$sLcdv6,
					Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'],
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID']
			));
			$aData['IMAGE'] = Pelican::$config['MEDIA_HTTP'] . $aData['MEDIA_PATH'];
		} elseif (
			$sLcdv6 != null &&
			$sFinition != null &&
			$sVersion == null
		) {
			$aVehicule = VehiculeGamme::getVehiculeByLCDVGamme(
					$sLcdv6, 'VP', $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']
			);
			$iAffichPrixCredit = Frontoffice_Zone_Helper::getAffichePrixCredit();
			$aData = Pelican_Cache::fetch("Frontend/Citroen/MonProjet/FinitionVehiculeByLcdv6", array(
					$sLcdv6,
					$sFinition,
					Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'],
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID']
			));

			$prixComptant = $aData['PRIMARY_DISPLAY_PRICE'];
			//$mLComptant		=	utf8_encode($aVehicule['VEHICULE_CASH_PRICE_LEGAL_MENTION']);
			$aData['PRICE_TYPE'] = $aVehicule['VEHICULE_CASH_PRICE_TYPE'];
			if (
				$iAffichPrixCredit == 2 ||
				(
				$iAffichPrixCredit == 1 &&
				(
				$_GET['values']['tpid'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] ||
				$_GET['values']['tpid'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']
				)
				)
			) {
				$prixCredit = $aData['CREDIT']['PRIX'];
				$mLCredit = utf8_encode($aData['CREDIT']['LEGAL_TEXT_LAGARDE']);
			}
			if (isset($aData['V3D_LCDV']) && $aData['V3D_LCDV'] != '') {
				$aData['IMAGE'] = Pelican::$config["VISUEL_3D_PATH"] . "?ratio=" . Pelican::$config['VISUEL_3D_PARAM']['RATIO'] . "&version=" . $aData['V3D_LCDV'] . "&quality=" . Pelican::$config['VISUEL_3D_PARAM']['QUALITY'] . "&width=373&format=png&height=209&view=" . Pelican::$config['VISUEL_3D_PARAM']['VIEW'] . "&client=" . Pelican::$config['VISUEL_3D_PARAM']['CLIENT'];
				$aData['IMAGE_MOBILE'] = Pelican::$config["VISUEL_3D_PATH"] . "?ratio=" . Pelican::$config['VISUEL_3D_PARAM']['RATIO'] . "&version=" . $aData['V3D_LCDV'] . "&quality=" . Pelican::$config['VISUEL_3D_PARAM']['QUALITY'] . "&width=93&format=png&height=93&view=" . Pelican::$config['VISUEL_3D_PARAM']['VIEW'] . "&client=" . Pelican::$config['VISUEL_3D_PARAM']['CLIENT'];
			}
		} else {
			$aData = Pelican_Cache::fetch("Frontend/Citroen/Versions", array(
					$sLcdv6,
					Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'],
					$sFinition,
					$sVersion,
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID']
			));
		}

		return $aData;
	}

	/**
	 * Données des menus déroulants en version mobile
	 */
	public function getVehiculesMobileAction()
	{
		$aData = Pelican_Cache::fetch("Frontend/Citroen/VehiculesDetailsParGamme", array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP']));
		$aVehicules = array(
			'label1' => t('CHOISIR_UN_MODELE'),
			'label2' => t('CHOISIR_UNE_FINITION'),
			'label3' => t('CHOISIR_UNE_MOTORISATION'),
			'baseVisuel' => Pelican::$config["VISUEL_3D_PATH"] . "?ratio=" . Pelican::$config['VISUEL_3D_PARAM']['RATIO'] . "&quality=" . Pelican::$config['VISUEL_3D_PARAM']['QUALITY'] . "&width=560&format=png&height=311&view=" . Pelican::$config['VISUEL_3D_PARAM']['VIEW'] . "&client=" . Pelican::$config['VISUEL_3D_PARAM']['CLIENT'] . "&version="
		);
		$aVehicules['vehicules'] = array();
		$i = -1;
		foreach ($aData as $idx => $data) {
			if ($aData[$idx]['LCDV6'] != $aData[$idx - 1]['LCDV6']) {
				$i++;
				$aVehicules['vehicules'][$i] = array();
				$aVehicules['vehicules'][$i]['id'] = $aData[$idx]['LCDV6'];
				$aVehicules['vehicules'][$i]['name'] = $aData[$idx]['VEHICULE_LABEL'];
				$aVehicules['vehicules'][$i]['visuel'] = Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat($aData[$idx]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['MOBILE_VEHICULE_SELECTIONNE']);
				$aVehicules['vehicules'][$i]['finitions'] = array();
				$j = -1;
			}
			if ($aData[$idx]['FINITION_CODE'] != $aData[$idx - 1]['FINITION_CODE']) {
				$j++;
				$aVehicules['vehicules'][$i]['finitions'][$j] = array();
				$aVehicules['vehicules'][$i]['finitions'][$j]['id'] = $aData[$idx]['FINITION_CODE'];
				$aVehicules['vehicules'][$i]['finitions'][$j]['name'] = $aData[$idx]['FINITION_LABEL'];
				$aVehicules['vehicules'][$i]['finitions'][$j]['codeVisuel'] = $aData[$idx]['V3D_LCDV'];
				$aVehicules['vehicules'][$i]['finitions'][$j]['versions'] = array();
				$k = -1;
			}
			if ($aData[$idx]['ENGINE_CODE'] != $aData[$idx - 1]['ENGINE_CODE'] || $aData[$idx]['FINITION_CODE'] != $aData[$idx - 1]['FINITION_CODE']) {
				$k++;
				$aVehicules['vehicules'][$i]['finitions'][$j]['versions'][$k] = array();
				$aVehicules['vehicules'][$i]['finitions'][$j]['versions'][$k]['id'] = $aData[$idx]['ENGINE_CODE'];
				$aVehicules['vehicules'][$i]['finitions'][$j]['versions'][$k]['name'] = $aData[$idx]['ENGINE_LABEL'];
				$aVehicules['vehicules'][$i]['finitions'][$j]['versions'][$k]['codeVisuel'] = $aData[$idx]['V3D_LCDV'];
			}
		}
		echo json_encode($aVehicules);
	}

	public function addToSelectionAjaxAction()
	{
		$aParams = $this->getParams();
		$oUser = \Citroen\UserProvider::getUser();
		if ($oUser && $oUser->isLogged()) {
			$user_id = $oUser->getId();
		} else {
			$user_id = null;
		}
		if (!isset($aParams['lcdv6_code']) && isset($aParams['values']) && count($aParams['values']) > 0) {
			//
			$order = $aParams['values']['order'];
			unset($aParams['values']['order']);
			// On recupere la valeur avec le plus de détail (CODE_MODEL|CODE_FINITION|CODE_MOTEUR)
			$data = "";
			$occurence = -1;
			foreach ($aParams['values'] as $val) {
				$nb = substr_count($val, '|');
				if ($nb > $occurence) {
					$occurence = $nb;
					$data = $val;
				}
			}
			$aArgs = explode('|', $data);
			if ($aArgs[1] == '0') {
				unset($aArgs[1]);
			}
			if ($aArgs[2] == '0') {
				unset($aArgs[2]);
			}
			SelectionVehicule::addToSelection($user_id, $order, $aArgs[0], $aArgs[1], $aArgs[2]);
		} elseif (isset($aParams['lcdv6_code'])) {
			SelectionVehicule::addToSelection($user_id, $aParams['order'], $aParams['lcdv6_code'], $aArgs[2], $aArgs[3]);
		}
		$_SESSION[APP]['VEHICULE_SELECTION_EDITION'] = false;
		if ($aParams['isForm']) {
			$json['message'] = t('MSG_CONFIRM_ADD_SELECT');
			echo json_encode($json);
		} else {
			$this->addResponseCommand('debug', array(1));
		}
	}

	public function removeFromSelectionAjaxAction()
	{
		$aParams = $this->getParams();
		$oUser = \Citroen\UserProvider::getUser();
		if (isset($aParams['order'])) {
			if ($oUser && $oUser->isLogged()) {
				$user_id = $oUser->getId();
			} else {
				$user_id = null;
			}
			SelectionVehicule::removeFromSelection($user_id, $aParams['order']);
			$this->addResponseCommand('debug', array(1));
		}
	}

	/**
	 * Méthodes de tests
	 */
	public function testAddToSelectionAction()
	{
		SelectionVehicule::addToSelection(31, '1CB0A5', 1, 'AC000024', 'ES000011');
	}

	public function testRemoveFromSelectionAction()
	{
		SelectionVehicule::removeFromSelection(31, 1);
	}

	public function setVehiculeActifAction()
	{
		$aParams = $this->getParams();
		if (isset($aParams['idx'])) {
			$_SESSION[APP]['VEHICULE_SELECTION'] = $aParams['idx'];
			$_SESSION[APP]['VEHICULE_SELECTION_EDITION'] = false;
		}
		$this->addResponseCommand('reload');
	}

	public function setVehiculeEditAction()
	{
		$aParams = $this->getParams();
		if (isset($aParams['idx'])) {
			$idx = $aParams['idx'];
			$_SESSION[APP]['VEHICULE_SELECTION'] = $idx;
			$_SESSION[APP]['VEHICULE_SELECTION_EDITION'] = true;

			$aVehiculesFromNavigation = Layout_Citroen_Comparateur_Controller::getVehiculeModeleFromNavigation();
			$this->assign('aVehicules', $aVehiculesFromNavigation);

			if ($oUser && $oUser->isLogged()) {
				$iUserId = $oUser->getId();
			} else {
				$iUserId = null;
			}
			$aVehiculeSelection = SelectionVehicule::getUserSelection($iUserId);
			$this->assign('aSelection', $aVehiculeSelection);

			$aSelectionDetails = array();
			if (is_array($aVehiculeSelection) && count($aVehiculeSelection)) {
				foreach ($aVehiculeSelection as $aSelection) {
					$aVehiculeInfo = self::getInfosVehicule(
							$aSelection['lcdv6_code'], $aSelection['finition_code'], $aSelection['version_code']
					);
					if (isset($aVehiculeInfo['VEHICULE_LABEL'])) {
						$aVehiculeInfo['LABEL'] = $aVehiculeInfo['VEHICULE_LABEL'];
						unset($arr['VEHICULE_LABEL']);
					}
					$aSelectionDetails[$aSelection['ordre']] = $aVehiculeInfo;
				}
			}
			$this->assign('aSelectionDetails', $aSelectionDetails);

			$this->assign('i', $idx);
			$this->assign('iEditionId', $idx);
			if ($this->isMobile()) {
				$this->_template = Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Citroen/MonProjet/SelectionVehicules/carSelectionForm.mobi';
			} else {
				$this->_template = Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Citroen/MonProjet/SelectionVehicules/carSelectionForm.tpl';
			}
			$this->fetch();
		}
	}

	/**
	 * Ajoute un nouveau véhicule à la liste de selection
	 */
	public function addSelectionAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		$user = \Citroen\UserProvider::getUser();
		$this->assign('user', $user);

		if ($user && $user->isLogged()) {
			$iUserId = $user->getId();
		} else {
			$iUserId = null;
		}
		$s = SelectionVehicule::getUserSelection($iUserId);
		$indice = sizeof($s);

		if ($indice < 3) {
			$temp = explode('|', $aParams['selection']);
			if (sizeof($temp) == 2) {
				$selection['LCDV6'] = $temp[1];
				$selection['FINITION_CODE'] = $temp[0];
			} else {
				$selection['LCDV6'] = $temp[1];
				$selection['FINITION_CODE'] = $temp[2];
				$selection['ENGINE_CODE'] = $temp[0];
			}

			SelectionVehicule::addToSelection($iUserId, $indice, $selection['LCDV6'], $selection['FINITION_CODE'], $selection['ENGINE_CODE']);

			$aSelectionDetails = array();
			$aVehiculeInfo = self::getInfosVehicule(
					$selection['LCDV6'], $selection['FINITION_CODE'], $selection['ENGINE_CODE']
			);
			if (isset($aVehiculeInfo['VEHICULE_LABEL'])) {
				$aVehiculeInfo['LABEL'] = $aVehiculeInfo['VEHICULE_LABEL'];
				unset($arr['VEHICULE_LABEL']);
			}
			$aSelectionDetails[$indice] = $aVehiculeInfo;
			$this->assign('aSelectionDetails', $aSelectionDetails);

			$this->assign('i', $indice);
			$this->_template = Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Citroen/MonProjet/SelectionVehicules/carSelectionDetails.tpl';
			$this->fetch();
		}
	}

	/**
	 * Met à jour le véhicule dans la liste de sélection pour un indice donné
	 */
	public function majSelectionAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		$user = \Citroen\UserProvider::getUser();
		$this->assign('user', $user);

		$temp = explode('|', $aParams['selection']);
		if (sizeof($temp) == 2) {
			$selection['LCDV6'] = $temp[1];
			$selection['FINITION_CODE'] = $temp[0];
		} else {
			$selection['LCDV6'] = $temp[1];
			$selection['FINITION_CODE'] = $temp[2];
			$selection['ENGINE_CODE'] = $temp[0];
		}

		$indice = $aParams['indice'];

		if ($user && $user->isLogged()) {
			$iUserId = $user->getId();
		} else {
			$iUserId = null;
		}
		SelectionVehicule::addToSelection($iUserId, $indice, $selection['LCDV6'], $selection['FINITION_CODE'], $selection['ENGINE_CODE']);

		$aSelectionDetails = array();
		$aVehiculeInfo = self::getInfosVehicule(
				$selection['LCDV6'], $selection['FINITION_CODE'], $selection['ENGINE_CODE']
		);
		if (isset($aVehiculeInfo['VEHICULE_LABEL'])) {
			$aVehiculeInfo['LABEL'] = $aVehiculeInfo['VEHICULE_LABEL'];
			unset($arr['VEHICULE_LABEL']);
		}
		$aSelectionDetails[$indice] = $aVehiculeInfo;
		$this->assign('aSelectionDetails', $aSelectionDetails);

		$this->assign('i', $indice);
		$this->assign('iSelectionId', $indice);
		$this->_template = Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Citroen/MonProjet/SelectionVehicules/carSelectionDetails.tpl';
		$this->fetch();
	}

}
