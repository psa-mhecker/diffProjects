<?php

//use Symfony\Component\EventDispatcher\EventDispatcher;
//use Citroen\Event\UserEvent;
//use Citroen\Event\UserEventSubscriber;
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(Pelican::$config['LIB_ROOT'] . '/External'),
	get_include_path(),
)));
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/Usermanager.php");
//require(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/Customerfields.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/Openid/Lightopenid.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/Abstract.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/Customer.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/Customermanager.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/Customerfields.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/CustomerAt/Vehicle.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/CustomerAt/Dealer.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/CustomerAt/User.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/CustomerAt/Subscription.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/HTTP/Request2/Exception.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/HTTP/Request2.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/HTTP/OAuth/Consumer/Request.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/HTTP/OAuth/Consumer.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/HTTP/OAuth/Store/Consumer/CacheLite.php");
require_once(Pelican::$config['LIB_ROOT'] . "/External/Cpw/GRCOnline/Customerxmlloader.php");

define('PUBLIC_PATH', \Pelican::$config['APPLICATION_LIBRARY'] . "/Citroen/User");

/**
 *
 */
class User_Controller extends Pelican_Controller_Front
{

	var $usrmng = null;
	var $customerMng = null;
	protected $_dispatcher;

	/*
	 *
	 */

	public function init()
	{
		//$this->_dispatcher = new EventDispatcher();
		//$this->_dispatcher->addSubscriber(new UserEventSubscriber());
		//$request = $this->getRequest();

		// Construction de la variable de configuration du webservice customer, qui sera fusionnée avec /application/configs/local/wscustomer.*.ini.php
		$customConfig = array();
		if( !empty(Pelican::$config['SITE']['INFOS']['SITE_WSCUSTOMER_CONSUMERKEY']) ){
			$customConfig['oauth']['consumerkey'] = Pelican::$config['SITE']['INFOS']['SITE_WSCUSTOMER_CONSUMERKEY'];
		}
		if( !empty(Pelican::$config['SITE']['INFOS']['SITE_WSCUSTOMER_CONSUMERSECRET']) ){
			$customConfig['oauth']['consumersecret'] = Pelican::$config['SITE']['INFOS']['SITE_WSCUSTOMER_CONSUMERSECRET'];
		}
		if( !empty(Pelican::$config['SITE']['INFOS']['SITE_WSCUSTOMER_SITECODE']) ){
			$customConfig['sitecode'] = Pelican::$config['SITE']['INFOS']['SITE_WSCUSTOMER_SITECODE'];
		}

		$this->usrmng = new Cpw_Usermanager();
		$this->customerMng = new Cpw_GRCOnline_Customermanager(Pelican::$config['APPLICATION_LIBRARY'] . "/Citroen/User/Wsdl/CRMDirect.wsdl", $customConfig);
	}

	/**
	 * Deconnexion
	 */
	public function deconnexionAction()
	{
		\Citroen\UserProvider::destroyRS();
		\Citroen\UserProvider::destroy();

		$this->getRequest()->addResponseCommand('script', array('value' => "top.location.reload();"));
	}

	/**
	 * Connexion via Facebook
	 */
	public function connexionFacebookAction()
	{
		// Déjà authentifié
		if ($_SESSION[APP]['FACEBOOK_ID']) {
			// Fermeture de la popop de connexion
			$this->setResponse("<script type=\"text/javascript\">self.close();</script>");
		}
		// Non authentifié
		else {
			//require_once(Pelican::$config['LIB_ROOT'] . "/External/Facebook/facebook.php");
			$facebook = new Citroen_Facebook(array(
				'appId' => Pelican::$config['FACEBOOK']['appId'],
				'secret' => Pelican::$config['FACEBOOK']['secret'],
				'oauth' => true
			));
			if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
				$facebook::$CURL_OPTS[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
				$facebook::$CURL_OPTS[CURLOPT_PROXY] = Pelican::$config['PROXY']['URL'];
				$facebook::$CURL_OPTS[CURLOPT_PROXYPORT] = Pelican::$config['PROXY']['PORT'];
				$facebook::$CURL_OPTS[CURLOPT_PROXYUSERPWD] = sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD']);
			}
			//$user_id = $facebook->getUser();
			$params = array(
				'redirect_uri' => Pelican::$config['DOCUMENT_HTTP'] . "/_/User/connexionFacebookCallback",
				'display' => "popup",
				'scope' => "email"
			);
			// Redirection vers la mire d'authentification Facebook
			$loginUrl = $facebook->getLoginUrl($params);
			header("Location: " . $loginUrl);
			die;
		}
	}

	/**
	 * Connexion via Facebook (Callback)
	 */
	public function connexionFacebookCallbackAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		$aPageProfil = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_PREFERENCES']));
		$sURLPageProfil = Pelican::$config['DOCUMENT_HTTP'] . $aPageProfil['PAGE_CLEAR_URL'];

		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		//require_once(Pelican::$config['LIB_ROOT'] . "/External/Facebook/facebook.php");
		$facebook = new Citroen_Facebook(array(
			'appId' => Pelican::$config['FACEBOOK']['appId'],
			'secret' => Pelican::$config['FACEBOOK']['secret'],
		));
		if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
			$facebook::$CURL_OPTS[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
			$facebook::$CURL_OPTS[CURLOPT_PROXY] = Pelican::$config['PROXY']['URL'];
			$facebook::$CURL_OPTS[CURLOPT_PROXYPORT] = Pelican::$config['PROXY']['PORT'];
			$facebook::$CURL_OPTS[CURLOPT_PROXYUSERPWD] = sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD']);
		}
		$user_id = $facebook->getUser();
		// Utilisateur logué par Facebook
		if ($user_id) {
			try {
				// Récupération des informations Facebook de l'utilisateur
				$fql = 'SELECT third_party_id,first_name,last_name,email FROM user WHERE uid = ' . $user_id;
				$user_profile = $facebook->api(array(
					'method' => 'fql.query',
					'query' => $fql
				));
				$user_profile = $user_profile[0];
				$_SESSION[APP]['facebook_profile'] = $user_profile;
				unset($_SESSION[APP]['twitter_profile']);
				unset($_SESSION[APP]['google_profile']);
				$_SESSION[APP]['FACEBOOK_ID'] = $user_profile['third_party_id'];
				// Test de la présence de l'utilisateur dans la base
				$oConnection = Pelican_Db::getInstance();
				$aBind[':facebook_id'] = $oConnection->strToBind($user_profile['third_party_id']);
				$sSQL = "select * from cpp_users where facebook_id = :facebook_id";
				$aUser = $oConnection->queryRow($sSQL, $aBind);
				// Utilisateur Facebook existant, on l'authentifie
				if ($aUser) {
					$user = new \Citroen\User($aUser['users_pk_id']);
					$user->setFacebookConnected();
					$user->setFacebookId($user_profile['third_party_id']);
					// Son profil est complet
					if ($aUser['users_accesstoken']) {
						// Son profil est actif
						if ($aUser['users_statut'] == 1) {
							$user->setCitroenId($aUser['users_accesstoken']);
							if ($aUser['twitter_id']) {
								$user->setTwitterId($aUser['twitter_id']);
							}
							if ($aUser['google_id']) {
								$user->setGoogleId($aUser['google_id']);
							}
							// Récuperations les informations de la BDI
							$this->customerMng->GetAccount($aUser['users_accesstoken']);
							foreach ($this->customerMng->data() as $key => $value) {
								if ($key == Cpw_GRCOnline_Customerfields::USR_EMAIL) {
									$user->setEmail($value);
								} elseif ($key == Cpw_GRCOnline_Customerfields::USR_FIRST_NAME) {
									$user->setFirstname($value);
								} elseif ($key == Cpw_GRCOnline_Customerfields::USR_LAST_NAME) {
									$user->setLastname($value);
								} elseif ($key == Cpw_GRCOnline_Customerfields::USR_CIVILITY) {
									$user->setCivility($value);
								}
							}
							// Recuperation des optins souscrites
							$_user = new Cpw_GRCOnline_CustomerAt_User();
							$_user->loadUser($user->getEmail());
							if ($_user->SubscriptionsActives != null) {
								foreach ($_user->SubscriptionsActives as $SubscriptionsActive) {
									if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays))
										$user->setOptinBrand(true);
									if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays))
										$user->setOptinDealer(true);
									if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays))
										$user->setOptinPartner(true);
								}
							}
							// Mise en session des infos de l'utilisateur
							\Citroen\UserProvider::setUser($user);
							$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion) . "'; self.close();</script>");
						}
						// Profil non activé
						else {
							// @TODO
							\Citroen\UserProvider::setUser($user);
						}
					}
					// Redirection vers formulaire d'inscription
					else {
						\Citroen\UserProvider::setUser($user);
						$_SESSION[APP]['iduser_temp_rs'] = $user->getId();
						$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?finalisationInscriptionRS")."'; self.close();</script>");
					}
				}
				// Autrement
				else {
					$user = \Citroen\UserProvider::getUser();
					die('cas1');
					// Utilsateur non Facebook, mais logué avec un CitroënID
					if ($user) {
						die('cas2');
						$user->setFacebookConnected();
						$user->setFacebookId($user_profile['third_party_id']);
						Pelican_Db::$values['users_pk_id'] = $user->getId();
						Pelican_Db::$values['facebook_id'] = $user->getFacebookId();
						$oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "cpp_users", "", array(), array('users_pk_id', 'facebook_id'));
						$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageProfil) . "'; self.close();</script>");
					}
					// Utilisateur non Facebook et non CitroënID, on crée un nouvel utilisateur avec le compte Facebook
					else {
						die('cas3');
						Pelican_Db::$values['facebook_id'] = $user_profile['third_party_id'];
						$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, 'cpp_users');
						// On l'authentifie temporairement
						$_SESSION[APP]['iduser_temp_rs'] = $oConnection->getLastOid();
						// Redirection vers formulaire d'inscription
						$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?finalisationInscriptionRS")."'; self.close();</script>");
					}
				}
			}
			// Redirection vers la page d'erreur technique
			catch (FacebookApiException $e) {
				// Redirection vers la page d'erreur technique
				$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1")."'; self.close();</script>");
			}
		}
		// Refus
		else {
			// Redirection vers la page d'erreur technique
			$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=2")."'; self.close();</script>");
		}
	}

	/**
	 * Connexion via Twitter
	 */
	public function connexionTwitterAction()
	{
		// Déjà autentifié
		if ($_SESSION[APP]['TWITTER_ACCESS_TOKEN']) {
			// Fermeture de la popop de connexion
			$this->setResponse("<script type=\"text/javascript\">self.close();</script>");
		}
		// Non authentifié
		else {
			require_once(Pelican::$config['LIB_ROOT'] . "/External/TwitterOAuth/twitteroauth.php");
			$proxy = NULL;
			if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
				$proxy = array(
					CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
					CURLOPT_PROXY => Pelican::$config['PROXY']['URL'],
					CURLOPT_PROXYPORT => Pelican::$config['PROXY']['PORT'],
					CURLOPT_PROXYUSERPWD => sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD'])
				);
			}
			$connection = new TwitterOAuth(Pelican::$config['TWITTER']['consumerKey'], Pelican::$config['TWITTER']['consumerSecret'], NULL, NULL, $proxy);
			$requestToken = $connection->getRequestToken(Pelican::$config['DOCUMENT_HTTP'] . "/_/User/connexionTwitterCallback");
			$_SESSION[APP]['TWITTER_OAUTH_TOKEN'] = $token = $requestToken['oauth_token'];
			$_SESSION[APP]['TWITTER_OAUTH_TOKEN_SECRET'] = $requestToken['oauth_token_secret'];
			$loginUrl = $connection->getAuthorizeURL($token);
			header('Location: ' . $loginUrl);
			die;
		}
	}

	/**
	 * Connexion via Twitter (Callback)
	 */
	public function connexionTwitterCallbackAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		$aPageProfil = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_PREFERENCES']));
		$sURLPageProfil = Pelican::$config['DOCUMENT_HTTP'] . $aPageProfil['PAGE_CLEAR_URL'];

		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		if (!$_GET['denied']) {
			require_once(Pelican::$config['LIB_ROOT'] . "/External/TwitterOAuth/twitteroauth.php");
			$proxy = NULL;
			if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
				$proxy = array(
					CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
					CURLOPT_PROXY => Pelican::$config['PROXY']['URL'],
					CURLOPT_PROXYPORT => Pelican::$config['PROXY']['PORT'],
					CURLOPT_PROXYUSERPWD => sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD'])
				);
			}
			$connection = new TwitterOAuth(Pelican::$config['TWITTER']['consumerKey'], Pelican::$config['TWITTER']['consumerSecret'], $_SESSION[APP]['TWITTER_OAUTH_TOKEN'], $_SESSION[APP]['TWITTER_OAUTH_TOKEN_SECRET'], $proxy);
			$accessToken = $connection->getAccessToken($_REQUEST['oauth_verifier']);
			if ($accessToken) {
				$_SESSION[APP]['TWITTER_ACCESS_TOKEN'] = $accessToken;
				$_SESSION[APP]['TWITTER_ID'] = $accessToken['user_id'];
				unset($_SESSION[APP]['TWITTER_OAUTH_TOKEN']);
				unset($_SESSION[APP]['TWITTER_OAUTH_TOKEN_SECRET']);
				$connection = new TwitterOAuth(Pelican::$config['TWITTER']['consumerKey'], Pelican::$config['TWITTER']['consumerSecret'], $accessToken['oauth_token'], $accessToken['oauth_token_secret'], $proxy);
				$content = $connection->get('account/verify_credentials');
				$_SESSION[APP]['twitter_profile'] = $content;
				unset($_SESSION[APP]['facebook_profile']);
				unset($_SESSION[APP]['google_profile']);
				// Test de la présence de l'utilisateur dans la base
				$oConnection = Pelican_Db::getInstance();
				$aBind[':twitter_id'] = $content->id;
				$sSQL = "select * from cpp_users where twitter_id = :twitter_id";
				$aUser = $oConnection->queryRow($sSQL, $aBind);
				// Utilisateur existant, on l'authentifie

				if ($aUser) {

					$user = new \Citroen\User($aUser['users_pk_id']);
					$user->setTwitterConnected();
					$user->setTwitterId($content->id);
					//$oEvent = new UserEvent(array('user'=>$user,'mode'=>'twitter'));
					//$this->_dispatcher->dispatch(UserEvent::LOGIN,$oEvent);
					// Son profil est complet
					if ($aUser['users_accesstoken']) {
						// Son profil est actif
						if ($aUser['users_statut'] == 1) {
							$user->setCitroenId($aUser['users_accesstoken']);
							if ($aUser['facebook_id']) {
								$user->setFacebookId($aUser['facebook_id']);
							}
							if ($aUser['google_id']) {
								$user->setGoogleId($aUser['google_id']);
							}
							// Récuperations les informations de la BDI
							$this->customerMng->GetAccount($aUser['users_accesstoken']);
							foreach ($this->customerMng->data() as $key => $value) {
								if ($key == Cpw_GRCOnline_Customerfields::USR_EMAIL) {
									$user->setEmail($value);
								} elseif ($key == Cpw_GRCOnline_Customerfields::USR_FIRST_NAME) {
									$user->setFirstname($value);
								} elseif ($key == Cpw_GRCOnline_Customerfields::USR_LAST_NAME) {
									$user->setLastname($value);
								} elseif ($key == Cpw_GRCOnline_Customerfields::USR_CIVILITY) {
									$user->setCivility($value);
								}
							}
							// Recuperation des optins souscrites
							$_user = new Cpw_GRCOnline_CustomerAt_User();
							$_user->loadUser($user->getEmail());
							if ($_user->SubscriptionsActives != null) {
								foreach ($_user->SubscriptionsActives as $SubscriptionsActive) {
									if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays))
										$user->setOptinBrand(true);
									if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays))
										$user->setOptinDealer(true);
									if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays))
										$user->setOptinPartner(true);
								}
							}
							// Mise en session des infos de l'utilisateur
							\Citroen\UserProvider::setUser($user);
							$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion) . "'; self.close();</script>");
						}
						// Profil non activé
						else {
							// @TODO
							\Citroen\UserProvider::setUser($user);
						}
					}
					// Redirection vers formulaire d'inscription
					else {
						\Citroen\UserProvider::setUser($user);
						$_SESSION[APP]['iduser_temp_rs'] = $user->getId();
						$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?finalisationInscriptionRS")."'; self.close();</script>");
					}
				}
				// Autrement
				else {
					$user = \Citroen\UserProvider::getUser();
					// Utilsateur non Twitter, mais logué avec un CitroënID
					if ($user) {
						$user->setTwitterConnected();
						$user->setTwitterId($content->id);
						Pelican_Db::$values['users_pk_id'] = $user->getId();
						Pelican_Db::$values['twitter_id'] = $user->getTwitterId();
						$oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "cpp_users", "", array(), array('users_pk_id', 'twitter_id'));
						$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageProfil) . "'; self.close();</script>");
					}
					// Utilsateur non Twitter et non CitroënID, on crée un nouvel utilisateur avec le compte Twitter
					else {
						Pelican_Db::$values['twitter_id'] = $content->id;
						$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, 'cpp_users');
						// On l'authentifie temporairement
						$_SESSION[APP]['iduser_temp_rs'] = $oConnection->getLastOid();
						// Redirection vers formulaire d'inscription
						$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?finalisationInscriptionRS")."'; self.close();</script>");
					}
				}
			}
			// Erreur
			else {
				// Redirection vers la page d'erreur technique
				$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=2")."'; self.close();</script>");
			}
		}
		// Refus
		else {
			// Redirection vers la page d'erreur technique
			$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=2")."'; self.close();</script>");
		}
	}

	/**
	 * Connexion via Google+
	 */
	public function connexionGoogleAction()
	{
		// Déjà autentifié
		if ($_SESSION[APP]['GOOGLE_ACCESS_TOKEN']) {
			// Fermeture de la popop de connexion
			$this->setResponse("<script type=\"text/javascript\">self.close();</script>");
		}
		// Non authentifié
		else {
			require_once(Pelican::$config['LIB_ROOT'] . "/External/Google/Google_Client.php");
			require_once(Pelican::$config['LIB_ROOT'] . "/External/Google/contrib/Google_PlusService.php");
			$client = new Google_Client();
			$client->setClientId(Pelican::$config['GOOGLE']['clientId']);
			$client->setClientSecret(Pelican::$config['GOOGLE']['clientSecret']);
			$client->setDeveloperKey(Pelican::$config['GOOGLE']['developerKey']);
			$client->setRedirectUri(Pelican::$config['DOCUMENT_HTTP'] . "/_/User/connexionGoogleCallback");
			$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'));
			$client->setApprovalPrompt('auto');
			if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
				$io = $client->getIo();
				$io->setOptions(array(
					CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
					CURLOPT_PROXY => Pelican::$config['PROXY']['URL'],
					CURLOPT_PROXYPORT => Pelican::$config['PROXY']['PORT'],
					CURLOPT_PROXYUSERPWD => sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD'])
				));
			}
			$plus = new Google_PlusService($client);
			// Recuperation du token d'acces en session
			if (isset($_SESSION[APP]['GOOGLE_ACCESS_TOKEN'])) {
				$client->setAccessToken($_SESSION[APP]['GOOGLE_ACCESS_TOKEN']);
			}
			// Redirection vers l'autentification si pas de token
			if (!$client->getAccessToken()) {
				$authUrl = $client->createAuthUrl();
				header("Location: " . $authUrl);
				die;
			}
		}
	}

	/**
	 * Connexion via Google+ (Callback)
	 */
	public function connexionGoogleCallbackAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		$aPageProfil = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_PREFERENCES']));
		$sURLPageProfil = Pelican::$config['DOCUMENT_HTTP'] . $aPageProfil['PAGE_CLEAR_URL'];

		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		require_once(Pelican::$config['LIB_ROOT'] . "/External/Google/Google_Client.php");
		require_once(Pelican::$config['LIB_ROOT'] . "/External/Google/contrib/Google_PlusService.php");
		require_once(Pelican::$config['LIB_ROOT'] . "/External/Google/contrib/Google_Oauth2Service.php");
		$client = new Google_Client();
		$client->setClientId(Pelican::$config['GOOGLE']['clientId']);
		$client->setClientSecret(Pelican::$config['GOOGLE']['clientSecret']);
		$client->setDeveloperKey(Pelican::$config['GOOGLE']['developerKey']);
		$client->setRedirectUri(Pelican::$config['DOCUMENT_HTTP'] . "/_/User/connexionGoogleCallback");
		if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
			$io = $client->getIo();
			$io->setOptions(array(
				CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
				CURLOPT_PROXY => Pelican::$config['PROXY']['URL'],
				CURLOPT_PROXYPORT => Pelican::$config['PROXY']['PORT'],
				CURLOPT_PROXYUSERPWD => sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD'])
			));
		}
		$plus = new Google_PlusService($client);
		if (isset($_GET['code'])) {
			$client->authenticate($_GET['code']);
			$_SESSION[APP]['GOOGLE_ACCESS_TOKEN'] = $client->getAccessToken();
			$me = $plus->people->get('me');
			$_SESSION[APP]['google_profile'] = $me;
			unset($_SESSION[APP]['facebook_profile']);
			unset($_SESSION[APP]['twitter_profile']);
			$_SESSION[APP]['GOOGLE_ID'] = $me['id'];
			// Test de la présence de l'utilisateur dans la base
			$oConnection = Pelican_Db::getInstance();
			$aBind[':google_id'] = $me['id'];
			$sSQL = "select * from cpp_users where google_id = :google_id";
			$aUser = $oConnection->queryRow($sSQL, $aBind);
			// Utilisateur existant, on l'authentifie
			if ($aUser) {
				$user = new \Citroen\User($aUser['users_pk_id']);
				$user->setGoogleConnected();
				$user->setGoogleId($me['id']);
				// Son profil est complet
				if ($aUser['users_accesstoken']) {
					// Son profil est actif
					if ($aUser['users_statut'] == 1) {
						$user->setCitroenId($aUser['users_accesstoken']);
						if ($aUser['facebook_id']) {
							$user->setFacebookId($aUser['facebook_id']);
						}
						if ($aUser['twitter_id']) {
							$user->setTwitterId($aUser['twitter_id']);
						}
						// Récuperations les informations de la BDI
						$this->customerMng->GetAccount($aUser['users_accesstoken']);
						foreach ($this->customerMng->data() as $key => $value) {
							if ($key == Cpw_GRCOnline_Customerfields::USR_EMAIL) {
								$user->setEmail($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_FIRST_NAME) {
								$user->setFirstname($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_LAST_NAME) {
								$user->setLastname($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_CIVILITY) {
								$user->setCivility($value);
							}
						}
						// Recuperation des optins souscrites
						$_user = new Cpw_GRCOnline_CustomerAt_User();
						$_user->loadUser($user->getEmail());
						if ($_user->SubscriptionsActives != null) {
							foreach ($_user->SubscriptionsActives as $SubscriptionsActive) {
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays))
									$user->setOptinBrand(true);
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays))
									$user->setOptinDealer(true);
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays))
									$user->setOptinPartner(true);
							}
						}
						// Mise en session des infos de l'utilisateur
						\Citroen\UserProvider::setUser($user);
						$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion) . "'; self.close();</script>");
					}
					// Profil non activé
					else {
						// @TODO
						\Citroen\UserProvider::setUser($user);
					}
				}
				// Redirection vers formulaire d'inscription
				else {
					\Citroen\UserProvider::setUser($user);
					$_SESSION[APP]['iduser_temp_rs'] = $user->getId();
					$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?finalisationInscriptionRS")."'; self.close();</script>");
				}
			}
			// Autrement
			else {
				$user = \Citroen\UserProvider::getUser();
				// Utilsateur non Google, mais logué avec un CitroënID
				if ($user) {
					$user->setGoogleConnected();
					$user->setGoogleId($me['id']);
					Pelican_Db::$values['users_pk_id'] = $user->getId();
					Pelican_Db::$values['google_id'] = $user->getGoogleId();
					$oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "cpp_users", "", array(), array('users_pk_id', 'google_id'));
					$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageProfil) . "'; self.close();</script>");
				}
				// Utilsateur non Google et non CitroënID, on crée un nouvel utilisateur avec le compte Google
				else {
					Pelican_Db::$values['google_id'] = $me['id'];
					$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, 'cpp_users');
					// On l'authentifie temporairement
					$_SESSION[APP]['iduser_temp_rs'] = $oConnection->getLastOid();
					// Redirection vers formulaire d'inscription
					$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?finalisationInscriptionRS")."'; self.close();</script>");
				}
			}
		}
		// Refus
		else {
			// Redirection vers la page d'erreur technique
			$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=2")."'; self.close();</script>");
		}
	}

	/**
	 * Redirection OpenID
	 */
	private function redirectOpenId($openidSrv, $immediate = false)
	{
		header('Location: ' . $openidSrv->authUrl($immediate));
		die();
	}

	/**
	 * Connexion via CitroenID
	 */
	public function openidAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
		$sCulture = $sLangue . "-" . $sPays;

		$request = $this->getRequest();
		$openIdCfg = $this->customerMng->getOpenId();
		$proxy = $this->customerMng->getProxy();
		// On instancie l'objet qui va gérer les échanges inter-serveurs pour l'OpenID
		$openid = new Cpw_Openid_Lightopenid($openIdCfg['realm'], $proxy);
		// On spécifie le mode
		$openid->mode = "setup";
		// On spécifie l'identity (le culture devra avoir une valeur dynamique selon le pays et le contexte pc/mobile)
		$openid->identity = $openIdCfg['identity'] . '?culture=' . $sCulture . '&context=' . ($this->isMobile() ? 'mobile' : 'pc');
		$openid->returnUrl = $openIdCfg['returnUrl'];
		// Cas de l'utilisateur qui annule la demande de connexion
		if ($request->getParam('error') == 'user_denied') {
			$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion) . "'; self.close();</script>");
		} else {
			if (!$openid->mode) {
				$this->redirectOpenId($openid, true);
			} else {
				// Si tu n'es pas connecté (id_res indique que tu es connecté), utilisateur redirigé pour connection
				if ($openid->mode != 'id_res') {
					$this->redirectOpenId($openid);
				}
				$urls = parse_url($request->getParam('openid_identity'));
				// Si le paramètre 'openid_identity' n'est pas renseigné, utilisateur redirigé pour connection
				if (!isset($urls['query'])) {
					$this->redirectOpenId($openid);
				}
				parse_str(urldecode($urls['query']), $params);
				//si le paramètre 'openid' n'est pas renseigné, utilisateur redirigé pour connection
				if (!isset($params, $params['openid'])) {
					$this->redirectOpenId($openid);
				}
				// Le paramètre OpenId représente le mail, on récupère ainsi l'utilisateur depuis notre base MySQL
				$oConnection = Pelican_Db::getInstance();
				$aBind[':users_email'] = $oConnection->strToBind($params['openid']);
				$sSQL = "select * from cpp_users where users_email = :users_email";
				$aUser = $oConnection->queryRow($sSQL, $aBind);
				// On définit l'url Oauth
				$actionlogin = $request->getBaseURL() . "/_/User/connexionCitroenId";
				// Si l'utilisateur existe et possède un accesstoken
				if ($aUser && $aUser['users_accesstoken']) {
					// Gestion du remember Me de la case à cocher de l'openID
					$this->customerMng->CheckSiteSubscriptions($aUser['users_accesstoken'], $sCulture);
					$remember = $this->customerMng->data();
					if (isset($remember->Properties, $remember->Properties->PropertyKey) && $remember->Properties->PropertyKey == 1) {
						setcookie("RememberMe", $aUser['users_pk_id'], time() + 3600);
					}
					// On teste la validité de cet accessToken
					$this->customerMng->CheckToken($aUser['users_accesstoken']);
					// Si aucune erreur est détectée
					if (!$this->customerMng->onError()) {
						// Appel du Customer Webservice pour avoir le compte associé au token
						$this->customerMng->GetAccount($aUser['users_accesstoken']);
						// On indique l'utilisateur comme connecté
						$_SESSION[APP]['accesstoken'] = $aUser['users_accesstoken'];
						$user = new \Citroen\User($aUser['users_pk_id']);
						$user->setCitroenId($_SESSION[APP]['accesstoken']);
						foreach ($this->customerMng->data() as $key => $value) {
							if ($key == Cpw_GRCOnline_Customerfields::USR_EMAIL) {
								$user->setEmail($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_FIRST_NAME) {
								$user->setFirstname($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_LAST_NAME) {
								$user->setLastname($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_CIVILITY) {
								$user->setCivility($value);
							}
						}
						// Recuperation des optins souscrites
						$_user = new Cpw_GRCOnline_CustomerAt_User();
						$_user->loadUser($user->getEmail());
						if ($_user->SubscriptionsActives != null) {
							foreach ($_user->SubscriptionsActives as $SubscriptionsActive) {
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays))
									$user->setOptinBrand(true);
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays))
									$user->setOptinDealer(true);
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays))
									$user->setOptinPartner(true);
							}
						}
						// Mise en session des infos de l'utilisateur
						\Citroen\UserProvider::setUser($user);
						// On ferme la popin avec rechargement automatique de la page pour afficher le menu en mode 'connecté'
						$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion) . "'; self.close();</script>");
					}
					// On redirigie l'utilisateur vers la gestion du partage (Oauth)
					else {
						header('Location: ' . $actionlogin);
						die();
					}
				}
				// On redirigie l'utilisateur vers la gestion du partage (Oauth)
				else {
					header('Location: ' . $actionlogin);
					die();
				}
			}
		}
	}

	public function connexionCitroenIdAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
		$sCulture = $sLangue . "-" . $sPays;

		// On teste si l'utilisateur n'est pas connecté
		if (!isset($_SESSION[APP]['accesstoken'])) {
			// Récupération de la configuration de OAuth
			$oauthCfg = $this->customerMng->getOauth();
			//$requestGet = $this->getRequest();
			// On récupère la configuration lié au proxy
			$proxy = $this->customerMng->getOauthProxy();
			// Instanciation de l'objet pour la protocol OAuth
			$httpRequest = new HTTP_Request2(null, $oauthCfg['method'], $proxy);
			$httpRequest->setHeader('Accept-Encoding', '.*');
			// Instanciation de protocol HTTP pour établier le lien avec Le serveur OAuth
			$request = new HTTP_OAuth_Consumer_Request;
			$request->accept($httpRequest);
			// Instanciation de l'objet OAuth Consumer
			$consumer = new HTTP_OAuth_Consumer($oauthCfg['consumerkey'], $oauthCfg['consumersecret']);
			$consumer->accept($request);
			try {
				// Récupération du requestToken en utilisant les informations de configuration OAauth.
				$consumer->getRequestToken($oauthCfg['urlrequesttoken'], $oauthCfg['urlcallback'], array(), $oauthCfg['method']);
				// Instanciation de l'objet OAuth Consumer pour le cache
				$store = new HTTP_OAuth_Store_Consumer_CacheLite();
				// Mise en cache des informations de OAuth
				$store->setRequestToken($consumer->getToken(), $consumer->getTokenSecret(), 'oauth', session_id());
				$urlparam = array('culture' => $sCulture, 'context' => ($this->isMobile()) ? 'mobile' : 'pc');
				$url = $consumer->getAuthorizeUrl($oauthCfg['urlauthorize'], $urlparam);
				// Redirection vers l'url Authorisation de partage.
				header("Location: " . $url);
				die;
			}
			// Une erreur d'OAuth Consumer s'est produite.
			catch (HTTP_OAuth_Consumer_Exception_InvalidResponse $e) {
				// Redirection vers la page d'erreur technique
				$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1")."'; self.close();</script>");
			}
			// Une erreur indéfinie s'est produite.
			catch (Exception $e) {
				// Redirection vers la page d'erreur technique
				$this->setResponse("<script type=\"text/javascript\">opener.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1")."'; self.close();</script>");
			}
		}
	}

	/**
	 * Connexion via CitroenID (Callback)
	 */
	public function connexionCitroenIdCallbackAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		// Instanciation de l'objet Oauth Consumer pour le cache
		$store = new HTTP_OAuth_Store_Consumer_CacheLite();
		// On récupère le token qui était stocké en cache.
		$tokens = $store->getRequestToken('oauth', session_id());
		if (isset($_SESSION[APP]['accesstoken']) || !isset($tokens)) {
			$this->setResponse("<script type=\"text/javascript\">top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=2")."';</script>");
		}
		$oauthCfg = $this->customerMng->getOauth();
		// Cas de refus du partage
		$requestGet = $this->getRequest();
		//$CheckError = $requestGet->getParam('error');
		if ($requestGet->getParam('error') == 'user_denied' || strlen($requestGet->getParam('denied')) > 0) {
			$this->setResponse("<script type=\"text/javascript\">top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=2")."';</script>");
		}
		// On récupère la configuration lié au Proxy OAuth
		$proxy = $this->customerMng->getOauthProxy();
		// Instanciation de l'objet pour la protocol OAuth
		$httpRequest = new HTTP_Request2(null, $oauthCfg['method'], $proxy);
		$httpRequest->setHeader('Accept-Encoding', '.*');
		// Instanciation de protocol HTTP pour établier le lien avec Le serveur OAuth
		$request = new HTTP_OAuth_Consumer_Request;
		$request->accept($httpRequest);
		// Instanciation de l'objet OAuth Consumer
		$consumer = new HTTP_OAuth_Consumer($oauthCfg['consumerkey'], $oauthCfg['consumersecret'], $tokens['token'], $tokens['tokenSecret']);
		$consumer->accept($request);
		try {
			// Récupération de l'accessToken en utilisant les informations de configuration Oauth et des paramètres de requetes du Provider OAuth
			$consumer->getAccessToken($oauthCfg['urlaccesstoken'], $_GET['oauth_verifier'], array(), $oauthCfg['method']);
			$token = $consumer->getToken();
			// Appel du Customer Webservice pour avoir le compte associé au token
			$this->customerMng->GetAccount($token);
			$email = '';
			// Si aucune erreur est détectée
			if (!$this->customerMng->onError()) {
				foreach ($this->customerMng->data() as $key => $value) {
					// On boucle l'ensemble des properties du compte pour trouver l'email.
					if ($key == Cpw_GRCOnline_Customerfields::USR_EMAIL) {
						// On affecte la valeur du mail pour une utilisation ultérieure
						$email = $value;
						break;
					}
				}
				// Si l'email n'est pas vide suite à la recherche
				if (strlen($email) != 0) {
					// Test de la présence de l'utilisateur dans la base
					$oConnection = Pelican_Db::getInstance();
					$aBind[':users_email'] = $oConnection->strToBind($email);
					$sSQL = "select * from cpp_users where users_email = :users_email";
					$aUser = $oConnection->queryRow($sSQL, $aBind);
					// Utilisateur existant dans la base
					if ($aUser) {
						// Mise à jour
						Pelican_Db::$values['users_pk_id'] = $aUser['users_pk_id'];
						Pelican_Db::$values['users_accesstoken'] = $token;
						$oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "cpp_users", "", array(), array('users_pk_id', 'users_accesstoken'));
						// Mise en Session pour activer la connexion de l'utilisateur
						$_SESSION[APP]['accesstoken'] = $token;
						$user = new \Citroen\User($aUser['users_pk_id']);
						$user->setCitroenId($token);
						foreach ($this->customerMng->data() as $key => $value) {
							if ($key == Cpw_GRCOnline_Customerfields::USR_EMAIL) {
								$user->setEmail($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_FIRST_NAME) {
								$user->setFirstname($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_LAST_NAME) {
								$user->setLastname($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_CIVILITY) {
								$user->setCivility($value);
							}
						}
						// Recuperation des optins souscrites
						$_user = new Cpw_GRCOnline_CustomerAt_User();
						$_user->loadUser($user->getEmail());
						if ($_user->SubscriptionsActives != null) {
							foreach ($_user->SubscriptionsActives as $SubscriptionsActive) {
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays))
									$user->setOptinBrand(true);
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays))
									$user->setOptinDealer(true);
								if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays))
									$user->setOptinPartner(true);
							}
						}
						// Mise en session des infos de l'utilisateur
						\Citroen\UserProvider::setUser($user);
						//
						//$oEvent = new UserEvent(array('user'=>$user));
						//$this->_dispatcher->dispatch(UserEvent::LOGIN,$oEvent);
						$this->setResponse("<script type=\"text/javascript\">top.location.href='" . Citroen\URL::parse($sURLPageConnexion) . "';</script>");
					}
					// Utilisateur inexistant dans la base
					else {
						Pelican_Db::$values['users_accesstoken'] = $token;
						Pelican_Db::$values['users_email'] = $email;
						$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, 'cpp_users');
						// On l'authentifie temporairement
						$_SESSION[APP]['iduser_temp_cid'] = $oConnection->getLastOid();
						$user = new \Citroen\User($_SESSION[APP]['iduser_temp_cid']);
						$user->setCitroenId($token, false);
						foreach ($this->customerMng->data() as $key => $value) {
							if ($key == Cpw_GRCOnline_Customerfields::USR_EMAIL) {
								$user->setEmail($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_FIRST_NAME) {
								$user->setFirstname($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_LAST_NAME) {
								$user->setLastname($value);
							} elseif ($key == Cpw_GRCOnline_Customerfields::USR_CIVILITY) {
								$user->setCivility($value);
							}
						}
						\Citroen\UserProvider::setUser($user);
						//
						$this->setResponse("<script type=\"text/javascript\">top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?finalisationInscriptionCID")."';</script>");
					}
				}
				// L'email récupéré via le ticket ne correspond à aucun compte
				else {
					$this->setResponse("<script type=\"text/javascript\">top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1")."';</script>");
				}
			}
			// Une erreur s'est produite lors de la demande getAccount
			else {
				$this->setResponse("<script type=\"text/javascript\">top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1")."';</script>");
			}
		}
		// Une erreur d'Oauth Consumer s'est produite.
		catch (HTTP_OAuth_Consumer_Exception_InvalidResponse $e) {
			$this->setResponse("<script type=\"text/javascript\">top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1")."';</script>");
		}
		// Une erreur indéfinie s'est produite.
		catch (Exception $e) {
			$this->setResponse("<script type=\"text/javascript\">top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1")."';</script>");
		}
	}

	/**
	 * Inscription
	 */
	public function inscriptionRSAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$aTemp = $this->getParams();
		$aParams = $aTemp['values'];

		// Supression des optins pour l'appel de la création du compte
		$OptIns = array();
		$OptIns['OFFER_BRAND'] = $aParams['OFFER_BRAND'];
		unset($aParams['OFFER_BRAND']);
		$OptIns['OFFER_DEALER'] = $aParams['OFFER_DEALER'];
		unset($aParams['OFFER_DEALER']);
		$OptIns['OFFER_PARTNER'] = $aParams['OFFER_PARTNER'];
		unset($aParams['OFFER_PARTNER']);

		$_email = $aParams['USR_EMAIL'];
		$_pwd = $aParams['USR_PASSWORD'];

		// On teste l'état du compte associé à l'email saisi par l'utilisateur
		$this->customerMng->CheckAccountStatus($aParams['USR_EMAIL']);
		// Si une erreur est détectée
		if ($this->customerMng->onError()) {
			// Redirection vers la page d'erreur avec le code erreur en question
			$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $this->customerMng->code()) . "'"));
		}
		$bdiStatut = $this->customerMng->data();
		// Si utilisateur est inconnu pour la BDI
		if ($bdiStatut == BDI_STATUS_UN) {
			// Affectation des données du formulaire pour la création du compte
			$bdivalues = $this->usrmng->GetCustomerDatas($aParams);
			// Appel du WS de création de compte
			$this->customerMng->CreateAccount($bdivalues);
			// Erreur à la création du compte
			if ($this->customerMng->onError()) {
				// On redirige l'utilisateur en affichant l'erreur
				$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $this->customerMng->code()) . "'"));
			}
			// Compte créé
			else {
				$user = new \Citroen\User($_SESSION[APP]['iduser_temp_rs']);
				Pelican_Db::$values['users_pk_id'] = $_SESSION[APP]['iduser_temp_rs'];
				Pelican_Db::$values['users_email'] = $aParams['USR_EMAIL'];
				$user->setEmail($aParams['USR_EMAIL']);
				$date = new DateTime();
				Pelican_Db::$values['users_dt_creation'] = $date->format('Y-m-d H:i:s');
				Pelican_Db::$values['users_statut'] = 0;
				Pelican_Db::$values['users_key'] = $this->_GeraHash(15);
				if ($_SESSION[APP]['FACEBOOK_ID']) {
					Pelican_Db::$values['facebook_id'] = $_SESSION[APP]['FACEBOOK_ID'];
					$user->setFacebookId(Pelican_Db::$values['facebook_id']);
					$user->setFacebookConnected();
				}
				if ($_SESSION[APP]['GOOGLE_ID']) {
					Pelican_Db::$values['google_id'] = $_SESSION[APP]['GOOGLE_ID'];
					$user->setGoogleId(Pelican_Db::$values['google_id']);
					$user->setGoogleConnected();
				}
				if ($_SESSION[APP]['TWITTER_ID']) {
					Pelican_Db::$values['twitter_id'] = $_SESSION[APP]['TWITTER_ID'];
					$user->setTwitterId(Pelican_Db::$values['twitter_id']);
					$user->setTwitterConnected();
				}
				// Mise à jour du compte dans la base CPP
				$oConnection = Pelican_Db::getInstance();
				$oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "cpp_users", "", array(), array('users_pk_id', 'users_email', 'users_dt_creation', 'users_statut', 'users_key', 'facebook_id', 'twitter_id', 'google_id'));
				$user->setFirstname($aParams['USR_FIRST_NAME']);
				$user->setLastname($aParams['USR_LAST_NAME']);
				$user->setCivility($aParams['USR_CIVILITY']);
				//
				if (sizeof($OptIns) > 0) {
					$this->customerMng->getTicket($_email, "SubscribeNewsletter", 86000);
					// On récupère le ticket fourni
					$ticket = $this->customerMng->data();
					$sbs = new Cpw_GRCOnline_CustomerAt_Subscription();
					$sbs->ConsumerCode = 'AC_FR_CPPV2';
					foreach ($OptIns as $optin => $value) {
						if ($optin == 'OFFER_BRAND' && $value == 'true') {
							$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays);
							$sbs->addSubscription($ticket);
						}
						if ($optin == 'OFFER_DEALER' && $value == 'true') {
							$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays);
							$sbs->addSubscription($ticket);
						}
						if ($optin == 'OFFER_PARTNER' && $value == 'true') {
							$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays);
							$sbs->addSubscription($ticket);
						}
					}
				}
				//
				\Citroen\UserProvider::setUser($user);
				unset($_SESSION[APP]['iduser_temp_rs']);
				// Redirection vers formulaire d'inscription
				$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?confirmationInscriptionRS")."';"));
			}
		}
		// Autrement,
		else {
			$sJS = "
				$('section.forminscription input').attr('disabled', 'disabled');
				$('section.forminscription input[name=\'email\']').removeAttr('disabled');
				$('section.forminscription a.valid').addClass('disabled');
				$('section.forminscription span.error').show();
				$('section.forminscription .alredayaccount').show();

				$('#personnal-datas input').attr('disabled', 'disabled');
				$('#personnal-datas select').attr('disabled', 'disabled');
				$('#personnal-datas input[name=\'email\']').removeAttr('disabled');
				$('#personnal-datas input[type=\'submit\']').addClass('disabled');
				$('#personnal-datas > span.error').show();
				$('#personnal-datas div.alredayaccount').show();
			";
			$this->getRequest()->addResponseCommand('script', array('value' => $sJS));
		}
	}

	/**
	 * Inscription
	 */
	public function inscriptionCIDAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$aTemp = $this->getParams();
		$aParams = $aTemp['values'];

		// Supression des optins pour l'appel de la création du compte
		$OptIns = array();
		$OptIns['OFFER_BRAND'] = $aParams['OFFER_BRAND'];
		unset($aParams['OFFER_BRAND']);
		$OptIns['OFFER_DEALER'] = $aParams['OFFER_DEALER'];
		unset($aParams['OFFER_DEALER']);
		$OptIns['OFFER_PARTNER'] = $aParams['OFFER_PARTNER'];
		unset($aParams['OFFER_PARTNER']);

		$_email = $aParams['USR_EMAIL'];
		$_pwd = $aParams['USR_PASSWORD'];

		$user = \Citroen\UserProvider::getUser();
		if ($user) {
			$bdivalues = $this->usrmng->GetCustomerDatas($aParams);
			// Tentative d'activation du compte avec le ticket reçu.
			$this->customerMng->UpdateAccount($bdivalues, $user->getCitroenId());
			// Si une erreur est détectée
			if ($this->customerMng->onError()) {
				// Utilisateur redirigé vers page d'erreur
				$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $this->customerMng->code()) . "'"));
			}
			$user->setFirstname($aParams['USR_FIRST_NAME']);
			$user->setLastname($aParams['USR_LAST_NAME']);
			$user->setCivility($aParams['USR_CIVILITY']);
			$user->setCitroenId($user->getCitroenId());
			//
			if (sizeof($OptIns) > 0) {
				$this->customerMng->getTicket($_email, "SubscribeNewsletter", 86000);
				// On récupère le ticket fourni
				$ticket = $this->customerMng->data();
				$sbs = new Cpw_GRCOnline_CustomerAt_Subscription();
				$sbs->ConsumerCode = 'AC_FR_CPPV2';
				foreach ($OptIns as $optin => $value) {
					if ($optin == 'OFFER_BRAND' && $value == 'true') {
						$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays);
						$sbs->addSubscription($ticket);
					}
					if ($optin == 'OFFER_DEALER' && $value == 'true') {
						$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays);
						$sbs->addSubscription($ticket);
					}
					if ($optin == 'OFFER_PARTNER' && $value == 'true') {
						$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays);
						$sbs->addSubscription($ticket);
					}
				}
			}
			//
			unset($_SESSION[APP]['iduser_temp_cid']);
			// Redirection vers formulaire d'inscription
			$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?confirmationInscriptionCID")."';"));
		}
	}

	/**
	 * Inscription
	 */
	public function inscriptionAction()
	{
		$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
		$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];

		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$aTemp = $this->getParams();
		$aParams = $aTemp['values'];
		// Supression des optins pour l'appel de la création du compte
		$OptIns = array();
		$OptIns['OFFER_BRAND'] = $aParams['OFFER_BRAND'];
		unset($aParams['OFFER_BRAND']);
		$OptIns['OFFER_DEALER'] = $aParams['OFFER_DEALER'];
		unset($aParams['OFFER_DEALER']);
		$OptIns['OFFER_PARTNER'] = $aParams['OFFER_PARTNER'];
		unset($aParams['OFFER_PARTNER']);
		$_email = $aParams['USR_EMAIL'];
		$_pwd = $aParams['USR_PASSWORD'];
		// On teste l'état du compte associé à l'email saisi par l'utilisateur
		$this->customerMng->CheckAccountStatus($_email);
		// Si une erreur est détectée
		if ($this->customerMng->onError()) {
			// Redirection vers la page d'erreur avec le code erreur en question
			$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $this->customerMng->code()) . "'"));
		}
		// On récupère la valeur du statut du compte
		$bdiStatut = $this->customerMng->data();
		$etape2 = false;
		// Si utilisateur est déjà inscrits et actif sur ce site
		if ($bdiStatut == BDI_STATUS_SS) {
			// On le redirige vers une page en le lui indiquant.
			$sJS = "
				$('section.forminscription input').attr('disabled', 'disabled');
				$('section.forminscription input[name=\'email\']').removeAttr('disabled');
				$('section.forminscription a.valid').addClass('disabled');
				$('section.forminscription span.error').show();
				$('section.forminscription .alredayaccount').show();

				$('.loading').remove();
				$('.container').removeClass('popopen');
				$('.content').removeClass('popopen');
				$('.content').removeAttr('style');
				$('#personnal-datas input').attr('disabled', 'disabled');
				$('#personnal-datas select').attr('disabled', 'disabled');
				var styles = {
					borderColor: \"lightgrey\",
					border: \"1px solid #D0D0D3\"
				};
				$('#personnal-datas select').css(styles);
				$('#personnal-datas input[name=\'email\']').removeAttr('disabled');
				$('#personnal-datas input[type=\'submit\']').addClass('disabled');
				$('#personnal-datas > span.error').show();
				$('#personnal-datas div.alredayaccount').show();
			";
			$this->getRequest()->addResponseCommand('script', array('value' => $sJS));
			//echo 'Le compte est déjà activé sur notre site.';
		} else {
			$user[Cpw_GRCOnline_Customerfields::USR_EMAIL] = $_email;
			$user[Cpw_GRCOnline_Customerfields::USR_PASSWORD] = $_pwd;
			// Stockage en session pour passage obligatoire à l'étape 2 de l'inscription
			$_SESSION[APP]['tmpUser'] = $user;
			// Si utilisateur est Non activé sur notre site.
			if ($bdiStatut == BDI_STATUS_NTA) {
				// Récupération de l'utilisateur dans MySQL
				$oConnection = Pelican_Db::getInstance();
				$aBind[':users_email'] = $oConnection->strToBind($_email);
				$sSQL = "select 1 from cpp_users where users_email = :users_email";
				$aUser = $oConnection->queryRow($sSQL, $aBind);
				// Si utilisateur existe dans notre base de données.
				if ($aUser) {
					// Transfert vers une page d'erreur pour indiquer et relancer un lien d'activation
					$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=2")."'"));
				} else {
					// Transfert de l'utilisateur dans la partie 2 de l'inscription
					$etape2 = true;
				}
			}
			// Si utilisateur est inconnu ou Not Initialiezd sur notre site.
			elseif ($bdiStatut == BDI_STATUS_UN || $bdiStatut == BDI_STATUS_NI) {
				// Transfert de l'utilisateur dans la partie 2 de l'inscription
				$etape2 = true;
			} else {
				// Si utilisateur est not Suscriber ou Known sur notre site.
				if ($bdiStatut == BDI_STATUS_NTS || $bdiStatut == BDI_STATUS_KN) {
					// Appel WS pour avoir un ticket Authentifié.
					$this->customerMng->GetAuthenticatedTicket($_email, $_pwd, 'Login', 86000, 1);
					// Test si une erreur se produit
					if (!$this->customerMng->onError()) {
						// Récupération du ticket Authentifié
						$ticket = $this->customerMng->data();
						// Récupération des informations de l'utilisateur par le GetAccount du Webservice.
						$this->customerMng->GetAccount($ticket);
						// Transformation des informations issu de l'objet du Webservice en Array et stockage en session.
						$_SESSION[APP]['tmpUser'] = $this->usrmng->GetUserDatas($user, $this->customerMng->data());
						// Transfert de l'utilisateur dans la partie 2 de l'inscription
						$etape2 = true;
					} else {
						// Redirection utilisateur pour affichage de l'erreur identifée
						$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $this->customerMng->code()) . "'"));
					}
				} else {
					// Redirection utilisateur pour affichage de l'erreur identifée
					$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $bdiStatut) . "'"));
				}
			}
		}
		if ($etape2) {
			$_isActivated = false;
			// Affection du User en Session
			$_user = $_SESSION[APP]['tmpUser'];
			// Récupération de l'email grâce au nom du champs de la classe Cpw_GRCOnline_Customerfields
			$_email = $_user[Cpw_GRCOnline_Customerfields::USR_EMAIL];
			// Récupération du mot de passe grâce au nom du champs de la classe Cpw_GRCOnline_Customerfields
			$_password = $_user[Cpw_GRCOnline_Customerfields::USR_PASSWORD];
			// On fait appel au webservice pour connaitre le statut du compte associé à l'email
			$this->customerMng->CheckAccountStatus($_email);
			// Si une erreur est détectée
			if ($this->customerMng->onError()) {
				// Redirection pour indiquer le type d'erreur
				$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $this->customerMng->code()) . "'"));
			}
			// Récupération de l'utilisateur dans MySQL.
			$oConnection = Pelican_Db::getInstance();
			Pelican_Db::$values['users_email'] = $_email;
			$aBind[':users_email'] = $oConnection->strToBind($_email);
			$sSQL = " select 1 from cpp_users where users_email = :users_email";
			$aUser = $oConnection->queryRow($sSQL, $aBind);
			// On récupère le statut du compte issu du Webservice.
			$bdiStatut = $this->customerMng->data();
			// Test si le compte récupéré par l'email n'existe pas.
			if (!$aUser) {
				// Initialisation de valeur de l'objet "User"
				Pelican_Db::$values['users_statut'] = 0;
				Pelican_Db::$values['users_key'] = $this->_GeraHash(15);
				$date = new DateTime();
				Pelican_Db::$values['users_dt_creation'] = $date->format('Y-m-d H:i:s');
				// Fin initialisation de l'objet "User"
				// Transformation des infos au format BDI
				// On transfert au format Customer Webservice les données du User qui ont été récupérées dans l'étape 1.
				$bdivalues = $this->usrmng->GetCustomerDatas($_user);
				// On met à jour et on rajoute selon remplissage du formulaire avec les données saisies par l'utilisateur.
				$bdivalues = $this->usrmng->GetCustomerDatas($aParams, $bdivalues);
				// Si le compte est a un statut UNKNOWN
				if ($bdiStatut == BDI_STATUS_UN) {
					// On utilise les informations précédemment transformées pour la création du compte avec le webservice.
					$this->customerMng->CreateAccount($bdivalues);
					// Si une erreur est détectée
					if ($this->customerMng->onError()) {
						// On redirige l'utilisateur en affichant l'erreur
						$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $this->customerMng->code()) . "'"));
					}
					// Ajoute l'utilisateur dans MySql
					$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, 'cpp_users');
				}
				// Si le compte a un statut NOT_SUSCRIBER
				if ($bdiStatut == BDI_STATUS_NTS) {
					// On va tenter de récupérer un ticket avec l'email et le password
					$this->customerMng->GetAuthenticatedTicket($_email, $_password, 'Subscribe', 86000, 1);
					// On récupère le ticket fourni
					$ticket = $this->customerMng->data();
					// On appele le Webservice pour ajouter notre site au compte utilisateur.
					$this->customerMng->Subscribe($ticket);
					// On va tenter de récupérer un ticket Authentifié.
					$this->customerMng->GetAuthenticatedTicket($_email, $_password, 'UpdateAccount', 86000, 1);
					// On récupère le ticket authentifié
					$ticket = $this->customerMng->data();
					// On va mettre à jour le compte avec les nouvelles informations saisies.
					$this->customerMng->UpdateAccount($bdivalues, $ticket);
					// Le compte est activé par défaut donc pas de clé à générer car pas de mail de validation
					Pelican_Db::$values['users_key'] = '';
					// Le compte est activé par défaut donc statut = 1
					Pelican_Db::$values['users_statut'] = 1;
					// On définit cette valeur comme activée.
					$_isActivated = true;
					// Met à jour l'utilisateur dans MySql
					$oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, 'cpp_users');
				}
				//
				if (sizeof($OptIns) > 0) {
					$this->customerMng->getTicket($_email, "SubscribeNewsletter", 86000);
					// On récupère le ticket fourni
					$ticket = $this->customerMng->data();
					$sbs = new Cpw_GRCOnline_CustomerAt_Subscription();
					$sbs->ConsumerCode = 'AC_FR_CPPV2';
					foreach ($OptIns as $optin => $value) {
						if ($optin == 'OFFER_BRAND' && $value == 'true') {
							$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays);
							$sbs->addSubscription($ticket);
						}
						if ($optin == 'OFFER_DEALER' && $value == 'true') {
							$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays);
							$sbs->addSubscription($ticket);
						}
						if ($optin == 'OFFER_PARTNER' && $value == 'true') {
							$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays);
							$sbs->addSubscription($ticket);
						}
					}
				}
				unset($_SESSION[APP]['tmpUser']);
				// Le message de fin de parcours d'inscription est modulé selon la valeur de cette variable
				if ($_isActivated) {
					$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?confirmationInscriptionCID")."';"));
				} else {
					$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?infoMail&email=" . $_email . "&key=" . Pelican_Db::$values['users_key']) . "'"));
				}
				// On vide la session car le parcours est terminé.
				// On redirige l'utilisateur vers la page de succès.
				//return $this->_helper->redirector('index');
			} else {
				// Test  si l'utilisateur est Not activated
				if ($bdiStatut == BDI_STATUS_NTA) {
					// Transfert vers une page d'erreur pour indiquer et relancer un lien d'activation
					$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?infoMail'")));
				} else {
					$this->getRequest()->addResponseCommand('script', array('value' => "top.location.href='" . Citroen\URL::parse($sURLPageConnexion . "?erreur=1&msg=" . $bdiStatut) . "'"));
				}
			}
		}
	}

	/**
	 * Activation du CitroënID (TEMPORAIRE)
	 * @TODO Redirection et provenance (email ?)
	 */
	public function activationAction()
	{
		$aParams = $this->getParams();

		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		// Page d'activation
		if ($aParams['email'] && $aParams['key']) {
			// Récupération de l'email
			$email = $aParams['email'];
			// Récupération de la clé
			$key = $aParams['key'];
		}
		// Activation par le lien présent dans la page de confirmation de création de compte
		else {
			$user = \Citroen\UserProvider::getUser();
			// Utilisateur logué
			if ($user) {
				$email = $user->getEmail();
				$oConnection = Pelican_Db::getInstance();
				$aBind[':users_email'] = $oConnection->strToBind($email);
				$sSQL = "select * from cpp_users where users_email = :users_email";
				$aUser = $oConnection->queryRow($sSQL, $aBind);
				if ($aUser) {
					$key = $aUser['users_key'];
				}
			}
		}
		// Si l'un des deux vide, redirigé vers page d'erreur
		if (strlen($email) == 0 || strlen($key) == 0) {
			echo "Une erreur s'est produite : Erreur de données";
		}
		// Récupération de l'utilisateur via l'Email
		$oConnection = Pelican_Db::getInstance();
		$aBind[':users_email'] = $oConnection->strToBind($email);
		$sSQL = "select * from cpp_users where users_email = :users_email";
		$aUser = $oConnection->queryRow($sSQL, $aBind);
		// Si les clé et les emails correspondent bien
		if (strtoupper($aUser['users_key']) == strtoupper($key)) {
			// Tentative de récupération d'un ticket.
			$this->customerMng->getTicket($aUser['users_email'], 'ActivatesAccount', '86000');
			// Si une erreur est détectée
			if ($this->customerMng->onError()) {
				// Utilisateur redirigé vers page d'erreur
				echo "Une erreur s'est produite :" . $this->customerMng->code();
			}
			// Stockage du ticket
			$ticket = $this->customerMng->data();
			// Tentative d'activation du compte avec le ticket reçu.
			$this->customerMng->ActivatesAccount($ticket);
			// Si une erreur est détectée
			if ($this->customerMng->onError()) {
				// Utilisateur redirigé vers page d'erreur
				echo "Une erreur s'est produite :" . $this->customerMng->code();
			}
			// On sauvegrade en base MySQL l'état de ce compte
			Pelican_Db::$values['users_pk_id'] = $aUser['users_pk_id'];
			Pelican_Db::$values['users_accesstoken'] = $this->customerMng->data();
			Pelican_Db::$values['users_key'] = '';
			Pelican_Db::$values['users_statut'] = 1;
			$oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "cpp_users", "", array(), array('users_pk_id', 'users_accesstoken', 'users_key', 'users_statut'));

			// Si l'utilsateur est authentifié, on le log (Cas provenant d'une connexion RS sans compte CitroenID, ou d'un connexion CitroenID sans compte CPPv2)
			if ($user) {
				$_SESSION[APP]['accesstoken'] = Pelican_Db::$values['users_accesstoken'];
				$user->setCitroenId(Pelican_Db::$values['users_accesstoken']);
				foreach ($this->customerMng->data() as $key => $value) {
					if ($key == Cpw_GRCOnline_Customerfields::USR_EMAIL) {
						$user->setEmail($value);
					} elseif ($key == Cpw_GRCOnline_Customerfields::USR_FIRST_NAME) {
						$user->setFirstname($value);
					} elseif ($key == Cpw_GRCOnline_Customerfields::USR_LAST_NAME) {
						$user->setLastname($value);
					} elseif ($key == Cpw_GRCOnline_Customerfields::USR_CIVILITY) {
						$user->setCivility($value);
					}
				}
				// Recuperation des optins souscrites
				$_user = new Cpw_GRCOnline_CustomerAt_User();
				$_user->loadUser($user->getEmail());
				if ($_user->SubscriptionsActives != null) {
					foreach ($_user->SubscriptionsActives as $SubscriptionsActive) {
						if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays))
							$user->setOptinBrand(true);
						if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays))
							$user->setOptinDealer(true);
						if ($SubscriptionsActive->SubscriptionCode == sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays))
							$user->setOptinPartner(true);
					}
				}
				// Mise en session des infos de l'utilisateur
				\Citroen\UserProvider::setUser($user);
			}
			// Autrement, l'utilisateur doit s'authentifier (Cas d'une inscription classique)
			else {

			}
			// Redirection vers la page Mon projet
			$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID'],
					Pelican::getPreviewVersion(),
					Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION'])
			);
			$sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'] . $aPageConnexion['PAGE_CLEAR_URL'];
			header("Location: " . $sURLPageConnexion);
			die;
			//echo "Félicitation, votre compte a été activé ! Vous pouvez vous connecter dès maintenant !";
		} else {
			echo "Vérifier bien les données issues du mail qui vous a été envoyé lors de la création de votre compte."; //si erreur de données
		}
	}

	/**
	 * Changement de mot de passe
	 */
	public function updatePasswordAction()
	{
		$aParams = $this->getParams();
		$user = \Citroen\UserProvider::getUser();
		if ($user && $user->isLogged()) {
			// Test de corresponace des mots de passe saisis.
			if (isset($aParams['users_password']) && isset($aParams['passwordConfirmation']) && ($aParams['users_password'] == $aParams['passwordConfirmation'])) {
				// Récupération du nouveau mot de passe
				$newpwd = $aParams['users_password'];
				// On tente de récupérer un ticket pour la modification du mot de passe
				$this->customerMng->getTicket($user->getEmail(), 'SetPassword', '86000');
				if ($this->customerMng->onError()) {
					// TODO Message Erreur
					$this->getRequest()->addResponseCommand('alert', array('value' => $this->customerMng->code()));
				}
				// On récupère la valeur du ticket.
				$ticket = $this->customerMng->data();
				// On lance la mise à jour du mot de passe avec le ticket.
				$this->customerMng->SetPassword($newpwd, $ticket);
				// Si une erreur est détectée
				if ($this->customerMng->onError()) {
					// TODO Message Erreur
					$this->getRequest()->addResponseCommand('alert', array('value' => $this->customerMng->code()));
				}
				// TODO Message confirmation
				$this->getRequest()->addResponseCommand('script', array('value' => 'OK'));
			} else {
				$this->getRequest()->addResponseCommand('alert', array('value' => 'confirmation incorect'));
			}
		} else {
			$this->getRequest()->addResponseCommand('alert', array('value' => 'non logué'));
		}
	}

	/**
	 * Mise à jour des informations dans la BDI
	 */
	public function majAction()
	{
		$aTemp = $this->getParams();
		$aParams = $aTemp['values'];
		$user = \Citroen\UserProvider::getUser();
		if ($user) {
			$bdivalues = $this->usrmng->GetCustomerDatas($aParams);
			// Tentative d'activation du compte avec le ticket reçu.
			$this->customerMng->UpdateAccount($bdivalues, $user->getCitroenId());
			// Si une erreur est détectée
			if ($this->customerMng->onError()) {
				// Utilisateur redirigé vers page d'erreur
				$this->getRequest()->addResponseCommand('script', array('value' => "$('section.mesPreferences .maj-ko').show();$('html, body').animate({scrollTop: $('.maj-ko').offset().top}, 200);"));
			}
			$user->setFirstname($aParams['USR_FIRST_NAME']);
			$user->setLastname($aParams['USR_LAST_NAME']);
			$user->setCivility($aParams['USR_CIVILITY']);
			$this->getRequest()->addResponseCommand('script', array('value' => "$('section.mesPreferences .maj-ok').show();$('html, body').animate({scrollTop: $('.maj-ok').offset().top}, 200);"));
		}
	}

	/**
	 * Mise à jour des informations Newsletter dans la BDI
	 */
	public function majNewslettersAction()
	{
		$aParams = $this->getParams();
		$user = \Citroen\UserProvider::getUser();
		if ($user) {
			$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
			// Appel WS pour récupération de ticket
			$this->customerMng->getTicket($user->getEmail(), "SubscribeNewsletter", 86000);
			// On récupère le ticket fourni
			$ticket_sub = $this->customerMng->data();
			// Appel WS pour récupération de ticket
			$this->customerMng->getTicket($user->getEmail(), "UnsubscribeNewsletter", 86000);
			// On récupère le ticket fourni
			$ticket_unsub = $this->customerMng->data();
			$sbs = new Cpw_GRCOnline_CustomerAt_Subscription();
			$sbs->ConsumerCode = 'AC_FR_CPPV2';
			if ($aParams['type'] == 'dealer') {
				$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_DEALER, $sPays);
				if ($aParams['value'] == 'true') {
					$sbs->addSubscription($ticket_sub);
					$user->setOptinDealer(true);
				} else {
					$sbs->deleteSubscription($ticket_unsub);
					$user->setOptinDealer(false);
				}
			}
			if ($aParams['type'] == 'brand') {
				$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_BRAND, $sPays);
				if ($aParams['value'] == 'true') {
					$sbs->addSubscription($ticket_sub);
					$user->setOptinBrand(true);
				} else {
					$sbs->deleteSubscription($ticket_unsub);
					$user->setOptinBrand(false);
				}
			}
			if ($aParams['type'] == 'partner') {
				$sbs->SubscriptionCode = sprintf(Cpw_GRCOnline_Customerfields::OFFER_PARTNER, $sPays);
				if ($aParams['value'] == 'true') {
					$sbs->addSubscription($ticket_sub);
					$user->setOptinPartner(true);
				} else {
					$sbs->deleteSubscription($ticket_unsub);
					$user->setOptinPartner(false);
				}
			}
		}
	}

	/**
	 * Test de validité de l'email
	 */
	public function emailValideAction()
	{
		$aTemp = $this->getParams();
		$aParams = $aTemp['values'];

		$sJS = "
			$('section.forminscription input').attr('disabled', 'disabled');
			$('section.forminscription input[name=\'email\']').removeAttr('disabled');
			$('section.forminscription a.valid').addClass('disabled');
			$('section.forminscription span.error').show();
			$('section.forminscription .alredayaccount').show();

			$('#personnal-datas input').attr('disabled', 'disabled');
			$('#personnal-datas select').attr('disabled', 'disabled');
			$('#personnal-datas input[name=\'email\']').removeAttr('disabled');
			$('#personnal-datas input[type=\'submit\']').addClass('disabled');
			$('#personnal-datas > span.error').show();
			$('#personnal-datas div.alredayaccount').show();
		";

		$_email = $aParams['USR_EMAIL'];
		// On teste l'état du compte associé à l'email saisi par l'utilisateur
		$this->customerMng->CheckAccountStatus($_email);
		if ($this->customerMng->onError()) {
			$this->getRequest()->addResponseCommand('script', array('value' => $sJS));
		}
		// On récupère la valeur du statut du compte
		$bdiStatut = $this->customerMng->data();
		// Si utilisateur est déjà inscrits et actif sur ce site
		if ($bdiStatut == BDI_STATUS_SS) {
			$this->getRequest()->addResponseCommand('script', array('value' => $sJS));
		} else {
			$this->getRequest()->addResponseCommand('script', array(''));
		}
	}

	public function infosAction()
	{
		$aParams = $this->getParams();
		$user = \Citroen\UserProvider::getUser();
		if ($user) {
			$this->customerMng->GetAccount($user->getCitroenId());
			print_r($this->customerMng->data());
		}
	}

	private function _GeraHash($qtd)
	{
		$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
		$QuantidadeCaracteres = strlen($Caracteres);
		$QuantidadeCaracteres--;
		$Hash = NULL;
		for ($x = 1; $x <= $qtd; $x++) {
			$Posicao = rand(0, $QuantidadeCaracteres);
			$Hash .= substr($Caracteres, $Posicao, 1);
		}
		return $Hash;
	}

}
