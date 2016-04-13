<?php

/**
 * Classe d'affichage Front de la tranche Connexion de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_Connexion_Controller extends Pelican_Controller_Front
{

	/**
	 *
	 */
	public function indexAction()
	{
		$user = \Citroen\UserProvider::getUser();
		// Utilisateur logué
		if (($user && $user->isLogged()) || isset($_GET['deconnexion'])) {
			if ($user->isFacebookConnected() || $user->isTwitterConnected() || $user->isGoogleConnected()) {
				$this->_forward('deconnexion');
			}
			// Affichage de la tranche de premiere authentification avec un CitroenID
			elseif (!isset($_COOKIE['PREMIERE_AUTHENTIFICATION'])) {
				$this->_forward('connecte');
			} else {
				$this->_forward('deconnexion');
			}
		}
		// Utilisateur non logué
		else {
			// Erreurs
			if (isset($_GET['erreur'])) {
				$this->_forward('connexion');
			}
			// Inscription
			elseif (isset($_GET['inscription'])) {
				$this->_forward('inscription');
			}
			// Finalisation inscription via CitroenID
			elseif (isset($_GET['finalisationInscriptionCID'])) {
				$this->_forward('finalisationInscriptionCID');
			}
			// Confirmation inscription via CitroenID
			elseif (isset($_GET['confirmationInscriptionCID'])) {
				$this->_forward('confirmationInscriptionCID');
			}
			// Finalisation inscription via les réseaux sociaux
			elseif (isset($_GET['finalisationInscriptionRS'])) {
				$this->_forward('finalisationInscriptionRS');
			}
			// Confirmation inscription via les réseaux sociaux
			elseif (isset($_GET['confirmationInscriptionRS'])) {
				$this->_forward('confirmationInscriptionRS');
			}
			// Confirmation inscription via les réseaux sociaux
			elseif (isset($_GET['infoMail'])) {
				$this->_forward('infoMail');
			}
			// Connexion
			elseif ($_SESSION[APP]['iduser_temp_cid'] || $_SESSION[APP]['iduser_temp_rs']) {
				\Citroen\UserProvider::destroyRS();
				\Citroen\UserProvider::destroy();
				$this->_forward('connexion');
			}
			// Connexion
			else {
				$this->_forward('connexion');
			}
		}
	}

	/**
	 *
	 */
	public function infosTransverse()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);
		$bAffichageFormulaire = false;

		if ($aParams['ZONE_PARAMETERS']) {
			$aConnexionRS = explode('|', $aParams['ZONE_PARAMETERS']);
			$this->assign('aConnexionRS', $aConnexionRS);
		}

		$user = \Citroen\UserProvider::getUser();
		$this->assign('user', $user);
		$this->assign('messageInfo', $_SESSION[APP]['PRJ_MESSAGE_CLOSE'] ? '' : 'withOutBorder');
	}

	/**
	 *
	 */
	public function deconnexionAction()
	{
		$this->infosTransverse();

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);

		$this->fetch();
	}

	/**
	 *
	 */

	public function erreurAction()
	{
		$aParams = $this->getParams();
		$this->infosTransverse();

		$aErreurs = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'GESTION_ERREURS'
		));
		$this->assign('aErreurs', $aErreurs[0]);

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);

		$this->fetch();
	}

	/**
	 *
	 */
	public function infoMailAction()
	{
		$aParams = $this->getParams();
		$this->infosTransverse();

		$this->fetch();
	}

	/**
	 * Formulaire d'inscription
	 */
	public function inscriptionAction()
	{
		$aParams = $this->getParams();
		$this->infosTransverse();
		$bAffichageFormulaire = true;

		$aInscription = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'INSCRIPTION'
		));
		$this->assign('aInscription', $aInscription[0]);

		$aNonIdentifie = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'NON_IDENTIFIE'
		));
		$this->assign('aNonIdentifie', $aNonIdentifie[0]);

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);
		$_GET['AFFICHAGE_FORMULAIRE'] = true;

		// Mentions légales
		if (isset($aParams['ZONE_TITRE7']) && $aParams['ZONE_TITRE7'] != "") {
			$pagePopUp = Pelican_Cache::fetch("Frontend/Page", array(
					$aParams['ZONE_TITRE7'],
					$aParams['SITE_ID'],
					$aParams['LANGUE_ID']
			));
			$this->assign('urlPopInMention', $pagePopUp["PAGE_CLEAR_URL"]);
            $this->assign('titlePopInMention', $pagePopUp["PAGE_TITLE"]);
		}
		if ($aParams['MEDIA_ID4']) {
			$mediaDetailPush4 = Pelican_Cache::fetch("Media/Detail", array(
					$aParams['MEDIA_ID4']
			));
			$this->assign('MEDIA_PATH4', $mediaDetailPush4["MEDIA_PATH"]);
			$this->assign('MEDIA_TITLE4', $mediaDetailPush4["MEDIA_TITLE"]);
		}
		$this->assign('aData', $aParams);

		$this->fetch();
	}

	/**
	 *
	 */
	public function finalisationInscriptionCIDAction()
	{
		$aParams = $this->getParams();
		$this->infosTransverse();
		$bAffichageFormulaire = true;

		$aInscription = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'INSCRIPTION'
		));
		$this->assign('aInscription', $aInscription[0]);

		$aFinalisationInscriptionCID = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'FINALISATION_INSCRIPTION_CITROENID'
		));
		$this->assign('aFinalisationInscriptionCID', $aFinalisationInscriptionCID[0]);

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);
		$_GET['AFFICHAGE_FORMULAIRE'] = true;

		$this->fetch();
	}

	/**
	 *
	 */
	public function confirmationInscriptionCIDAction()
	{

		$aParams = $this->getParams();
		$this->infosTransverse();

		$aInscription = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'INSCRIPTION'
		));
		$this->assign('aInscription', $aInscription[0]);

		$aConfirmationInscriptionCID = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'CONFIRMATION_INSCRIPTION'
		));
		$this->assign('aConfirmationInscriptionCID', $aConfirmationInscriptionCID[0]);

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);

		$this->fetch();
	}

	/**
	 * @todo ajouter la prise en charge des donnée rapatriée depuis google+/twitter
	 */
	public function finalisationInscriptionRSAction()
	{
		$aParams = $this->getParams();
		$this->infosTransverse();
		$bAffichageFormulaire = true;

		$aInscription = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'INSCRIPTION'
		));
		$this->assign('aInscription', $aInscription[0]);

		$aFinalisationInscriptionRS = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'FINALISATION_INSCRIPTION_RS'
		));
		//set fb user data
		$aRsProfile = array();
		if (isset($_SESSION[APP]['facebook_profile'])) {
			$aRsProfile['first_name'] = $_SESSION[APP]['facebook_profile']['first_name'];
			$aRsProfile['last_name'] = $_SESSION[APP]['facebook_profile']['last_name'];
			$aRsProfile['email'] = $_SESSION[APP]['facebook_profile']['email'];
		}
		if (isset($_SESSION[APP]['twitter_profile'])) {
			$aRsProfile['last_name'] = $_SESSION[APP]['twitter_profile']->name;
		}
		if (isset($_SESSION[APP]['google_profile'])) {
			$aRsProfile['first_name'] = $_SESSION[APP]['google_profile']['name']['givenName'];
			$aRsProfile['last_name'] = $_SESSION[APP]['google_profile']['name']['familyName'];
			$aRsProfile['email'] = $_SESSION[APP]['google_profile']['emails'][0]['value'];
			;
		}

		$this->assign('aRsProfile', $aRsProfile);

		$this->assign('aFinalisationInscriptionRS', $aFinalisationInscriptionRS[0]);

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);
		$_GET['AFFICHAGE_FORMULAIRE'] = true;


		$this->fetch();
	}

	/**
	 *
	 */
	public function confirmationInscriptionRSAction()
	{
		$aParams = $this->getParams();
		$this->infosTransverse();

		$aConfirmationInscriptionRS = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'CONFIRMATION_INSCRIPTION'
		));
		$this->assign('aConfirmationInscriptionRS', $aConfirmationInscriptionRS[0]);

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);

		$this->fetch();
	}

	/**
	 *
	 */
	public function connecteAction()
	{
		$aParams = $this->getParams();
		$this->infosTransverse();

		$aConnecte = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'CONNECTE'
		));
		$this->assign('aConnecte', $aConnecte[0]);

		setcookie('PREMIERE_AUTHENTIFICATION', 1, date('U', strtotime("+1 year")), "/");

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);

		$this->fetch();
	}

	/**
	 *
	 */
	public function connexionAction()
	{
		$aParams = $this->getParams();
		$this->infosTransverse();

		$aNonIdentifie = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'NON_IDENTIFIE'
		));
		$this->assign('aNonIdentifie', $aNonIdentifie[0]);

		$aErreurs = Pelican_Cache::fetch("Frontend/Citroen/ZoneMulti", array(
				$aParams['pid'],
				$aParams['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				$aParams['ZONE_TEMPLATE_ID'],
				'GESTION_ERREURS'
		));
		$this->assign('aErreurs', $aErreurs[0]);

		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];
		$this->assign('sURLPageConnexion', $sURLPageConnexion);

		$this->assign('bAffichageFormulaire', $bAffichageFormulaire);

		$this->fetch();

		// Le code HTML de la tranche est stocké en session pour l'utilisation dans la tranche derniere comparaison
		$_SESSION[APP]['TEMP_BLOC_CONNEXION'] = $this->getResponse();

		// La tranche de connexion est affiché sous la tranche de derniere comparaison
		if (isset($_GET['COMPARER']) && !isset($_SESSION[APP]['COMPARATEUR_PERSO'])) {
			$this->assign('bBlocMasque', true);
		}

		$this->fetch();
	}

}
