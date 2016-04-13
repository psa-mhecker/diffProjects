<?php

/**
 * Class ConcessionFavoris gérant les concessions favorites
 *
 * @author Ayoub Hidrii <ayoub.hidri@businessdecision.com>
 */

namespace Citroen;

class ConcessionFavoris
{

	public static $cookieName = 'pdv_bookmark'; // Nom du cookie de sauvegarde des points de vente
	public static $cookieLifetime = 1296000; // Durée du cookie PDV (15 jours)

	/**
	 * Cas 1. L'utilisateur est connecté à mon projet sur CPPV2 et la concession n'est pas dans
	 * ses favoris > Affichage du bouton « Ajouter à vos établissements favoris ».
	 *   Au clic : la concession est ajoutée, le bouton est alors transformé en « concession favorite ».
	 * Cas 2. L'utilisateur n'est pas connecté à mon projet sur CPPV2 et la concession n'est pas dans ses favoris > Affichage du bouton « Ajouter à vos établissements favoris »
	 *   Au clic : la concession est ajoutée, le bouton est alors transformé en « concession favorite ».
	 */

	public static function addToFavs($sUserId = null, $sConcessionId, $sType, $bWithUser = true)
	{
		if ($sUserId) {
			$aFavs = self::getFavorisConcessionsFromDB($sUserId);
			if (
				($sUserId == $_SESSION[APP]['USER']->getId()) &&
				(is_array($aFavs))
			) {
				if (!isset($aFavs[$sType]) || $sConcessionId != $aFavs[$sType]) {
					self::addConcessionToUser($sUserId, $sConcessionId);
				}
			}
		} else {
			self::setFavorisConcessionsFromSession($sType, $sConcessionId);
		}
		$sButtonText = t('FAVORITE_CONCESSION');
		return array('button_text' => $sButtonText);
	}

	/**
	 * Récupère les favoris enregistrés dans le compte de l'utilisateur, stocké dans la base de données
	 */
	public static function getFavorisConcessionsFromDB($sUserId = null)
	{
		$aFavs = array();
		if ($sUserId) {
			$aFavs = \Pelican_Cache::fetch("Frontend/Citroen/MonProjet/MesConcessions", array($sUserId));
		}

		return $aFavs;
	}

	/**
	 * Récupère les PDV favoris (favoris_vn/favoris_av) depuis le cookie
	 */
	public static function getFavorisConcessionsFromSession()
	{
		$currentCookie = isset($_COOKIE[self::$cookieName]) ? json_decode($_COOKIE[self::$cookieName], true) : array('favoris_vn' => null, 'favoris_av' => null);
		return $currentCookie;
	}

	/**
	 * Défini la valeur du favori de type $sType dans le cookie
	 */
	public static function setFavorisConcessionsFromSession($sType, $value)
	{
		$cookie = self::getFavorisConcessionsFromSession();
		$cookie[$sType] = $value;
		\Citroen_View_Helper_Cookie::setCookie(self::$cookieName, json_encode($cookie), time() + self::$cookieLifetime, '/');
	}

	/**
	 * Supprime les favoris enregistrés en cookie
	 */
	public static function removeFavorisConcessionsFromSession($sType)
	{
		self::setFavorisConcessionsFromSession($sType, null);
	}

	/**
	 * Marque le point de vente $sConcessionId comme favori pour l'utilisateur $sUserId (DB uniquement)
	 */
	public static function addConcessionToUser($sUserId = null, $sConcessionId, $sType = 'favoris_vn')
	{
		require_once(\Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/CustomerAt/Dealer.php");
		if ($sType == 'favoris_vn' || $sType == 'favoris_av') {
			// Ajout en base BDI (Citroen via GRCOnline)
			$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
			$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
			$aDealer = \Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array($sConcessionId, $sPays, $sLangue));
			$oDealer = new \Cpw_GRCOnline_CustomerAt_Dealer();
			$oDealer->CountryCode = $sPays . $sConcessionId;
			if ($aDealer['lat'] && $aDealer['lng']) {
				$oDealer->LatLong = $aDealer['lat'] . " " . $aDealer['lng'];
			}
			if ($aDealer['name']) {
				$oDealer->Name = $aDealer['name'];
			}
			if ($aDealer['addressDetail']['Line1']) {
				$oDealer->Adress1 = $aDealer['addressDetail']['Line1'];
			}
			if ($aDealer['addressDetail']['Line2']) {
				$oDealer->Adress2 = $aDealer['addressDetail']['Line2'];
			}
			if ($aDealer['addressDetail']['ZipCode']) {
				$oDealer->PostCode = $aDealer['addressDetail']['ZipCode'];
			}
			if ($aDealer['addressDetail']['City']) {
				$oDealer->Town = $aDealer['addressDetail']['City'];
			}
			if ($aDealer['addressDetail']['Country']) {
				$oDealer->Country = $aDealer['addressDetail']['Country'];
			}
			if ($sType == 'favoris_vn') {
				$oDealer->Type = 'relatedgeositepreferredvn';
			}
			if ($sType == 'favoris_av') {
				$oDealer->Type = 'relatedgeositepreferredapv';
			}
			$result = $oDealer->addDealer($_SESSION[APP]['USER']->getCitroenId());
			if ($result) {
				// Ajout en base CPPv2
				$oConnection = \Pelican_Db::getInstance();
				\Pelican_Db::$values['users_pk_id'] = $sUserId;
				\Pelican_Db::$values[$sType] = $sConcessionId;
				$oConnection->updateTable(\Pelican_Db::DATABASE_UPDATE, 'cpp_users', '', array(), array($sType, 'users_pk_id'));
				\Pelican_Cache::clean("Frontend/Citroen/MonProjet/MesConcessions", array($sUserId));
			}
		}
	}

	/**
	 * Supprime le point de vente favoris
	 */
	public static function removeConcessionFromUser($sUserId = null, $sConcessionId, $sType = 'favoris_vn')
	{
		require_once(\Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/CustomerAt/Dealer.php");
		if ($sType == 'favoris_vn' || $sType == 'favoris_av') {
			// Supression en base BDI (Citroen via GRCOnline)
			$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
			$oDealer = new \Cpw_GRCOnline_CustomerAt_Dealer();
			$oDealer->CountryCode = $sPays . $sConcessionId;
			if ($sType == 'favoris_vn') {
				$oDealer->Type = 'relatedgeositepreferredvn';
			}
			if ($sType == 'favoris_av') {
				$oDealer->Type = 'relatedgeositepreferredapv';
			}
			$oDealer->deleteDealer($_SESSION[APP]['USER']->getCitroenId());
			// Supression en base CPPv2
			$oConnection = \Pelican_Db::getInstance();
			\Pelican_Db::$values['users_pk_id'] = $sUserId;
			\Pelican_Db::$values[$sType] = null;
			$oConnection->updateTable(\Pelican_Db::DATABASE_UPDATE, 'cpp_users', '', array(), array($sType, 'users_pk_id'));
			self::removeFavorisConcessionsFromSession($sType);
			\Pelican_Cache::clean("Frontend/Citroen/MonProjet/MesConcessions", array($sUserId));
		}
	}

}

?>