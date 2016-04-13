<?php

use Citroen\ConcessionFavoris;

/**
 * Classe d'affichage Front des tranches Concession VN/AV de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_ConcessionVNAV_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{

		$aParams = $this->getParams();
		if (isset($aParams['ZONE_TEMPLATE_LABEL']) && $aParams['ZONE_TEMPLATE_LABEL'] == "Concession VN") {
			$trancheConcession = 1;
		} elseif (isset($aParams['ZONE_TEMPLATE_LABEL']) && $aParams['ZONE_TEMPLATE_LABEL'] == "Concession APV") {
			$trancheConcession = 2;
		}

		$oUser = \Citroen\UserProvider::getUser();
		if ($oUser && $oUser->isLogged()) {
			$iUserId = $oUser->getId();
			$aFavs = ConcessionFavoris::getFavorisConcessionsFromDB($iUserId);
			/* if (!count($aFavs)) {
			  $aFavs = array(
			  'favoris_av' => $_SESSION['favoris_av'],
			  'favoris_vn' => $_SESSION['favoris_vn']
			  );
			  } */
		} else {
			$aFavs = ConcessionFavoris::getFavorisConcessionsFromSession();
		}


		$aOutil = Pelican_Cache::fetch("Frontend/Citroen/VehiculeOutil", array(
				$aParams['SITE_ID'],
				$aParams['LANGUE_ID'],
				$aParams['ZONE_TOOL'],
				"WEB"
		));


		if (is_array($aOutil) && !empty($aOutil)) {
			foreach ($aOutil as $key => $OneOutil) {
				if ($OneOutil['BARRE_OUTILS_MODE_OUVERTURE'] == 3) {
					Pelican::$config['DEPLOYABLE'] ++;
					Pelican::$config['DEPLOYABLE_BLOC'][Pelican::$config['DEPLOYABLE']] = $OneOutil['BARRE_OUTILS_FORMULAIRE'];
					$aOutil[$key]['BARRE_OUTILS_URL_WEB'] = '#deployable_' . Pelican::$config['DEPLOYABLE'];
				}
			}
		}

		$this->assign("aOutil", $aOutil);

		$aDealers[$aParams['ZONE_PARAMETERS']] = $this->getDealerDetails($aFavs[$aParams['ZONE_PARAMETERS']]);
		$this->assign('bHasDealer', (boolean) count($aDealers[$aParams['ZONE_PARAMETERS']]));
		$this->assign('aFavs', $aFavs[$aParams['ZONE_PARAMETERS']]);
		$this->assign('aDealers', $aDealers);

		$this->assign('trancheConcession', $trancheConcession);
		$this->assign('aData', $aParams);
		$aSite = Pelican_Cache::fetch("Frontend/Site", array(
				$_SESSION[APP]['SITE_ID']
		));

		$mapGoogleKey = $aSite['DNS'][$_SERVER['SERVER_NAME']]['map_google_key'];
		$mapGoogleClient = $aSite['DNS'][$_SERVER['SERVER_NAME']]['map_google'];
		$urlToSign = '/maps/api/staticmap?center=' . $aDealers[$aParams['ZONE_PARAMETERS']]['lat'] . ',' . $aDealers[$aParams['ZONE_PARAMETERS']]['lng'] . '&zoom=16&size=288x288&maptype=roadmap&sensor=false&client=' . $mapGoogleClient . '&language=&markers=icon:' . Pelican::$config['IMAGE_FRONT_HTTP'] . '/picto/marker2.png|label:none|' . $aDealers[$aParams['ZONE_PARAMETERS']]['lat'] . ',' . $aDealers[$aParams['ZONE_PARAMETERS']]['lng'] . '';
		$decodedKey = base64_decode(str_replace(array('-', '_'), array('+', '/'), $mapGoogleKey));
		$signature = hash_hmac("sha1", $urlToSign, $decodedKey, true);
		$encodedSignature = str_replace(array('+', '/'), array('-', '_'), base64_encode($signature));
		$urlMaps = $urlToSign . '&signature=' . $encodedSignature;

		$this->assign('user', $oUser);
		$this->assign('urlMaps', $urlMaps);

		$this->fetch();
	}

	public function getStoreListAction()
	{
		$aParams = $this->getParams();

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		// Zone
		$aZone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
				$aParams['page'],
				$aParams['version'],
				$aParams['ztid'],
				$_SESSION[APP]['LANGUE_ID'],
				$aParams['area'],
				$aParams['order']
		));

		$aDealers = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/DealerList", array(
				$aParams['lat'],
				$aParams['long'],
				$sPays,
				$sLangue,
				$aZone['ZONE_ATTRIBUT'],
				$aParams['request'],
				$aZone['ZONE_TITRE13']
		));
		echo json_encode($aDealers);
	}

	/**
	 * @todo: dynamiser les variables de langues.
	 */
	public function getDealerAction()
	{
		$aData = $this->getParams();

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$aDealer = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
				$aData['id'],
				$sPays,
				$sLangue
		));

		echo json_encode($aDealer);
	}

	/**
	 * @todo: dynamiser les variables de langues.
	 */
	public function getMapConfigurationAction()
	{
		$aParams = $this->getParams();

		// Zone
		$aZone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
				$aParams['page'],
				$aParams['version'],
				$aParams['ztid'],
				$_SESSION[APP]['LANGUE_ID'],
				$aParams['area'],
				$aParams['order']
		));

		/**
		 *  Page globale
		 */
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));

		/**
		 *  Configuration
		 */
		$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$pageGlobal['PAGE_ID'],
				Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
				$pageGlobal['PAGE_VERSION'],
				$_SESSION[APP]['LANGUE_ID']
		));

		$bRegroupement = ($aZone['ZONE_CRITERIA_ID2'] == 1) ? true : false;
		$bAutocompletion = ($aZone['ZONE_CRITERIA_ID3'] == 1) ? true : false;

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$lat = ($aParams['lat'] != '') ? $aParams['lat'] : $aConfiguration['ZONE_MAP_LATITUDE'];
		$lng = ($aParams['lng'] != '') ? $aParams['lng'] : $aConfiguration['ZONE_MAP_LONGITUDE'];

		$aParamsCache = array(
			$aConfiguration['ZONE_ATTRIBUT'],
			10,
			1,
			$aZone['ZONE_ATTRIBUT2'],
			$aZone['ZONE_ATTRIBUT3'],
			$lat,
			$lng,
			$sLangue,
			$sPays,
			$aZone['ZONE_ATTRIBUT'],
			$bRegroupement,
			$bAutocompletion,
			'',
			$aZone['ZONE_TITRE13']
		);

		$aConfig = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/MapConf", array(
				implode('##', $aParamsCache)
		));

		echo json_encode($aConfig);
	}

	public function addToFavorisAjaxAction()
	{
		$oUser = \Citroen\UserProvider::getUser();
		(!is_null($oUser)) ? $iIdUser = $oUser->getId() : null;
		$aParams = $this->getParams();
		$sSid = $aParams['sid'];
		$sType = $aParams['type'];
		$aResults = array();

		if (!empty($sSid)) {
			$aResults = ConcessionFavoris::addToFavs($iIdUser, $sSid, $sType);
			$_SESSION[APP][$sType] = $sSid;
		}
		$this->assign('aResults', $aResults);
		$this->fetch();

		if ($aResults['button_text']) {

			$this->getRequest()->addResponseCommand("assign", array(
				'id' => sprintf('add_to_favs_%s', $sSid),
				'attr' => 'innerHTML',
				'value' => $this->getResponse()
			));

			$this->getRequest()->addResponseCommand(
				'script', array(
				'value' => "document.location.reload(true)"
				)
			);
		}
	}

	public function deleteFromFavsAjaxAction()
	{
		$aParams = $this->getParams();

		if (isset($aParams['sid'])) {
			$oUser = \Citroen\UserProvider::getUser();
			(!is_null($oUser)) ? $iIdUser = $oUser->getId() : null;
			if ($iIdUser) {
				ConcessionFavoris::removeConcessionFromUser($iIdUser, $aParams['sid']);
			} else {
				ConcessionFavoris::removeFavorisConcessionsFromSession($aParams['sid']);
			}
		}
		$this->getRequest()->addResponseCommand(
			'script', array(
			'value' => "document.location.reload(true)"
			)
		);
	}

	/**
	 *
	 */
	protected function getDealerDetails($sDid)
	{

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$aDealer = array();

		if (!empty($sDid)) {
			$aDealer = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
					$sDid,
					$sPays,
					$sLangue
			));
		}
		return $aDealer;
	}

}
