<?php

namespace Citroen;

/**
 * Fichier de Citroen : langues
 *
 * Classe de gestion de la séléction de véhicules sous Mon Projet
 *
 * @package Citroen
 * @subpackage SelectionVehicule
 * @author Ayoub Hidri <ayoub.hidri@businessdecision.com>
 * @since 04/11/2013
 */
class SelectionVehicule
{

	public static function addToSelection($iUserId = null, $iOrder = null, $sLcdv6, $sFinitionCode, $sEngineCode)
	{
		$aVehicule = \Pelican_Cache::fetch(
				'Frontend/Citroen/VehiculeByLCDVGamme', array(
				$sLcdv6,
				\Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'],
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID']
				)
		);
		\Pelican_Db::$values['vehicule_id'] = $aVehicule['VEHICULE_ID'];
		if (!is_null($iUserId)) {
			$oConnection = \Pelican_Db::getInstance();
			\Pelican_Db::$values['citroen_user_id'] = $iUserId;
			\Pelican_Db::$values['lcdv6_code'] = $sLcdv6;
			\Pelican_Db::$values['finition_code'] = $sFinitionCode;
			\Pelican_Db::$values['version_code'] = $sEngineCode;
			\Pelican_Db::$values['ordre'] = $oConnection->strToBind($iOrder);
			$aBind = array(
				':ORDRE' => $iOrder,
				':USER_ID' => $iUserId
			);
			$oConnection->replaceQuery('#pref#_selection_vehicules', 'ordre=:ORDRE AND citroen_user_id=:USER_ID', array(), array(), $aBind);
			\Pelican_Cache::clean('Frontend/Citroen/MonProjet/SelectionVehicules');
		} else {
			// Récuperation des véhicules en session
			$aSelection = $_SESSION[APP]['selectionVehicule'];
			// Suppression du véhicule pour index donné
			unset($aSelection[$iOrder]);
			// Ajout du véhicule à l'index donné
			$aSelection[$iOrder] = array(
				'ordre' => $iOrder,
				'lcdv6_code' => $sLcdv6,
				'finition_code' => $sFinitionCode,
				'version_code' => $sEngineCode,
				'vehicule_id' => $aVehicule['VEHICULE_ID']
			);
			// MAJ Session avec les véhicules sélectionnés
			$_SESSION[APP]['selectionVehicule'] = $aSelection;
			// MAJ Cookie avec les véhicules sélectionnés
			if ($_SESSION[APP]['USE_COOKIES'] == true) {
				\Citroen_View_Helper_Cookie::setCookie('selectionVehicule', $aSelection, 0, "/", "", false, false, true);
			}
		}

		//$oConnection->updateTable(\Pelican_Db::DATABASE_UPDATE, '#pref#_selection_vehicules','',array(),array());
	}

	public static function removeFromSelection($iUserId, $iOrder)
	{
		if ($iUserId != null) {
			$oConnection = \Pelican_Db::getInstance();
			\Pelican_Db::$values['citroen_user_id'] = $iUserId;
			\Pelican_Db::$values['ordre'] = $iOrder;
			$oConnection->updateTable(\Pelican_Db::DATABASE_DELETE, '#pref#_selection_vehicules', '', array(), array('citroen_user_id', 'ordre'));
			\Pelican_Cache::clean('Frontend/Citroen/MonProjet/SelectionVehicules');
		} else {
			unset($_SESSION[APP]['selectionVehicule'][$iOrder]);
			if (isset($_SESSION[APP]['USE_COOKIES']) && $_SESSION[APP]['USE_COOKIES'] == true) {
				$aSelection = $_SESSION[APP]['selectionVehicule'];
				if (count($aSelection) > 0) {
					\Citroen_View_Helper_Cookie::setCookie('selectionVehicule', $aSelection, 0, "/", "", false, false, true);
				} else {
					\Citroen_View_Helper_Cookie::setCookie('selectionVehicule', "", time() - 3600, "/");
				}
			}
		}
	}

	public static function getUserSelection($iUserId)
	{
		$aSelection = array();
		// Si l'utilsateur est logué
		if (!is_null($iUserId)) {
			$aSelection = \Pelican_Cache::fetch('Frontend/Citroen/MonProjet/SelectionVehicules', array($iUserId));
		} else {
			// Si l'utililsateur a accepté les cookies
			if (isset($_SESSION[APP]['USE_COOKIES']) && $_SESSION[APP]['USE_COOKIES'] == true) {
				// Si la session n'est pas vide
				if (isset($_SESSION[APP]['selectionVehicule'])) {
					$aSelection = $_SESSION[APP]['selectionVehicule'];
					\Citroen_View_Helper_Cookie::setCookie('selectionVehicule', $aSelection, 0, "/", "", false, false, true);
				}
				$_SESSION[APP]['selectionVehicule'] = \Citroen_View_Helper_Cookie::getCookie('selectionVehicule', true);
			}
			$aSelection = $_SESSION[APP]['selectionVehicule'];
		}
		return $aSelection;
	}

}
