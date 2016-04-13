<?php
/**
 * Classe métier Subscriber
 * 
 * @package Pelican
 * @subpackage User_Subscriber
 * @author __AUTHOR__
 * @since 24/09/2009
 * @version 1.0
 */

pelican_import ( 'Security.Crypt' );
pelican_import ( 'User' );
// include_once(pelican_path('External.Simplemail'));

/**
 * Classe métier Subscriber
 *
 * @package Pelican
 * @subpackage User_Subscriber
 * @author Cédric Fuseau <cedric.fuseau@businessdecision.com>
 * @since 24/09/2009
 * @version 1.0
 */
class Pelican_User_Subscriber extends Pelican_User {
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var int
	 */
	private $iSiteId;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sAddress;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sCity;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sCivilite;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var date
	 */
	private $dDateBirthday;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var date
	 */
	private $dDateRecord;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var date
	 */
	private $dDateRecordFormat;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var date
	 */
	private $dDateUnregistered;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sFax;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sFirstname;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var int
	 */
	private $iId;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var int
	 */
	private $iIsNewsletter;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sLastname;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sEmail;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sMobilePhone;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sPassword;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var int
	 */
	private $iZipCode;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var int
	 */
	private $iIsOnline;
	
	/**
	 * __DESC__
	 *
	 * @access private
	 * @var string
	 */
	private $sCountry;
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return int
	 */
	public function getSiteId() {
		return $this->iSiteId;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getAddress() {
		return $this->sAddress;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getNickname() {
		return $this->sNickname;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getCity() {
		return $this->sCity;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getCivilite() {
		return $this->sCivilite;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return date
	 */
	public function getDateBirthday() {
		return $this->dDateBirthday;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return date
	 */
	public function getDateRecord() {
		return $this->dDateRecord;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return date
	 */
	public function getDateRecordFormat() {
		return $this->dDateRecordFormat;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getFax() {
		return $this->sFax;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getFirstname() {
		return $this->sFirstname;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return int
	 */
	public function getId() {
		return $this->iId;
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getLastname() {
		return $this->sLastname;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getEmail() {
		return $this->sEmail;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getMobilePhone() {
		return $this->sMobilePhone;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getPassword() {
		return $this->sPassword;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return string
	 */
	public function getZipCode() {
		return $this->iZipCode;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return int
	 */
	public function getIsOnline() {
		return $this->iIsOnline;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @return Date
	 */
	public function getCountry() {
		return $this->sCountry;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $iSiteId int
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setSiteId($iSiteId) {
		$this->iSiteId = $iSiteId;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sNickname int
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setNickname($sNickname) {
		$this->sNickname = $sNickname;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sAddress string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setAddress($sAddress) {
		$this->sAddress = $sAddress;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sCity string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setCity($sCity) {
		$this->sCity = $sCity;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sCivilite string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setCivilite($sCivilite) {
		$this->sCivilite = $sCivilite;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $dDateBirthday date
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setDateBirthday($dDateBirthday) {
		$this->dDateBirthday = $dDateBirthday;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $dDateRecord date
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setDateRecord($dDateRecord) {
		$this->dDateRecord = $dDateRecord;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $dDateRecordFormat __TYPE__
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setDateRecordFormat($dDateRecordFormat) {
		$this->dDateRecordFormat = $dDateRecordFormat;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sFax string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setFax($sFax) {
		$this->sFax = $sFax;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sFirstname string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setFirstname($sFirstname) {
		$this->sFirstname = $sFirstname;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $iId int
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setId($iId) {
		$this->iId = $iId;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sLastname string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setLastname($sLastname) {
		$this->sLastname = $sLastname;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sEmail string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setEmail($sEmail) {
		$this->sEmail = $sEmail;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sMobilePhone string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setMobilePhone($sMobilePhone) {
		$this->sMobilePhone = $sMobilePhone;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $sPassword string
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setPassword($sPassword) {
		$this->sPassword = $sPassword;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $iZipCode int
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setZipCode($iZipCode) {
		$this->iZipCode = $iZipCode;
	}
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $iIsOnline int
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setIsOnline($iIsOnline) {
		$this->iIsOnline = $iIsOnline;
	}
	/**
	 *
	 * @param
	 *       	 string sCountry
	 *       	
	 * @access public
	 * @param $sCountry __TYPE__
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function setCountry($sCountry) {
		$this->sCountry = $sCountry;
	}
	
	public $session_label = 'subscriber';
	
	/**
	 * Destructeur
	 *
	 * @access public
	 * @return __TYPE__
	 */
	public function __destruct() {
		foreach ( get_object_vars ( $this ) as $iKey => $sValue ) {
			$this->$iKey = null;
		}
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $subscriber_id bool
	 *       	 (option) __DESC__
	 * @param $email bool
	 *       	 (option) __DESC__
	 * @param $site_id bool
	 *       	 (option) __DESC__
	 * @param $subscriber bool
	 *       	 (option) __DESC__
	 * @param $mdp bool
	 *       	 (option) __DESC__
	 * @param $isOnline bool
	 *       	 (option) __DESC__
	 * @return __TYPE__
	 */
	public function __construct($subscriber_id = false, $email = false, $site_id = false, $subscriber = false, $mdp = false, $isOnline = false) {
	
	}
	
	/**
	 * Load subscriber
	 *
	 * @access public
	 * @param $aSubscriber int
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function load($aSubscriber) {
		if (is_array ( $aSubscriber ) && (count ( $aSubscriber ) > 0)) {
			
			$this->iId = $aSubscriber ["SUBSCRIBER_ID"];
			$this->iSiteId = $aSubscriber ['SITE_ID'];
			$this->sAddress = $aSubscriber ["SUBSCRIBER_ADDRESS"];
			$this->sCity = $aSubscriber ["SUBSCRIBER_CITY"];
			$this->sCivilite = $aSubscriber ["CIVILITE_ID"];
			$this->sFax = $aSubscriber ["SUBSCRIBER_FAX"];
			$this->sFirstname = $aSubscriber ["SUBSCRIBER_FIRSTNAME"];
			$this->sLastname = $aSubscriber ["SUBSCRIBER_LASTNAME"];
			$this->sEmail = $aSubscriber ["SUBSCRIBER_EMAIL"];
			$this->sPassword = $aSubscriber ["SUBSCRIBER_PASSWORD"];
			$this->iZipCode = $aSubscriber ["SUBSCRIBER_ZIP_CODE"];
			$this->sNickname = $aSubscriber ["SUBSCRIBER_NICKNAME"];
			$this->sCountry = $aSubscriber ["COUNTRY_ID"];
		
		}
	}
	
	/**
	 * __DESC__
	 *
	 * @static
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 * @access public
	 * @staticvar xx $_instance
	 * @return __TYPE__
	 */
	public static function getInstance() {
		static $_instance;
		
		if (! is_object ( $_instance )) {
			$_instance = new self ();
		}
		
		return $_instance;
	}
	
	/**
	 * Méthode d'authentification
	 *
	 * @access public
	 * @param $mail string
	 *       	 __DESC__
	 * @param $password string
	 *       	 __DESC__
	 * @return Pelican_Auth_Result
	 */
	public function login($mail, $password) {
		
		$this->controlAttempt ();
		
		$oCrypt = Pelican_Factory::getInstance ( 'Security.Crypt' );
		$password = $oCrypt->encrypt3DES ( $password );
		
		$auth = Pelican_Auth::getInstance ();
		$authAdapter = new Pelican_Auth_Adapter_Db_Basic ();
		$authAdapter->setIdentity ( $mail );
		$authAdapter->setCredential ( $password );
		$authAdapter->setConfig ( array ('#pref#_subscriber', 'SUBSCRIBER_EMAIL', 'SUBSCRIBER_PASSWORD' ) );
		// Pour gestion du stockage des données d'authentification en session
		$authStorage = new Pelican_Auth_Storage_Session ( APP, $this->session_label );
		$auth->setStorage ( $authStorage );
		$result = $auth->authenticate ( $authAdapter );
		$this->setData ( $_SESSION [APP] [$this->session_label] );
		$this->endAttempt ();
		$this->getRights ();
		return ($result);
	}
	
	/**
	 * Fonction de changement de mail d'un inscrit
	 *
	 * @access public
	 * @param $subscriber_id int
	 *       	 __DESC__
	 * @param $subscriber_new_mail string
	 *       	 __DESC__
	 * @return bool
	 */
	public function subscriber_change_mail($subscriber_id, $subscriber_new_mail) {
		
		$oConnection = Pelican_Db::getInstance ();
		
		if ($subscriber_id && $subscriber_new_mail) {
			$aBind = array ();
			$aBind [":SUBSCRIBER_ID"] = $subscriber_id;
			$aBind [":SUBSCRIBER_EMAIL"] = $oConnection->strToBind ( $subscriber_new_mail );
			$sSql = "... requête ...";
			$oConnection->query ( $sSql, $aBind );
			
			/*
			 * A ajouter - mise en session si besoin
			 */
			
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Fonction de changement du Pelican_Security_Password d'un inscrit
	 *
	 * @access public
	 * @param $subscriber_id int
	 *       	 __DESC__
	 * @param $subscriber_new_password __TYPE__
	 *       	 __DESC__
	 * @param $site_id __TYPE__
	 *       	 __DESC__
	 * @return bool
	 */
	public function subscriber_change_password($subscriber_id, $subscriber_new_password, $site_id) {
		
		$oConnection = Pelican_Db::getInstance ();
		
		if ($subscriber_id && $subscriber_new_password) {
			
			$oCrypt = Pelican_Factory::getInstance ( 'Security.Crypt' );
			$aBind = array ();
			$aBind [":SUBSCRIBER_ID"] = $subscriber_id;
			$aBind [":SUBSCRIBER_PASSWORD"] = $oConnection->strToBind ( $oCrypt->encrypt3DES ( $subscriber_new_password ) );
			$aBind [":SITE_ID"] = $site_id;
			$strSQL = "    UPDATE     #pref#_subscriber
	                            SET        SUBSCRIBER_PASSWORD = :SUBSCRIBER_PASSWORD
	                            WHERE    SITE_ID = :SITE_ID
	                            AND        SUBSCRIBER_ID = :SUBSCRIBER_ID
	                ";
			$oConnection->query ( $strSQL, $aBind );
			
			/*
			 * A ajouter - mise en session si besoin
			 */
			
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Fonction d'envoi du Pelican_Security_Password à un subscriber
	 *
	 * @access public
	 * @param $subscriber_email string
	 *       	 __DESC__
	 * @param $site_id int
	 *       	 __DESC__
	 * @return bool
	 */
	public function subscriber_send_password_by_email($subscriber_email, $site_id) {
		
		require_once (pelican_path ( 'External.Simplemail' ));
		$oConnection = Pelican_Db::getInstance ();
		$oCrypt = Pelican_Factory::getInstance ( 'Security.Crypt' );
		
		$aBind = array ();
		$aBind [":EMAIL"] = $oConnection->strToBind ( strtolower ( $subscriber_email ) );
		$aBind [":SITE_ID"] = $site_id;
		
		$strSQL = "    SELECT  SUBSCRIBER_PASSWORD
	                        FROM     #pref#_subscriber 
	                        WHERE     LOWER(SUBSCRIBER_EMAIL) = :EMAIL
	                        AND     SITE_ID = :SITE_ID
	                    ";
		$result = $oConnection->queryItem ( $strSQL, $aBind );
		if ($result) {
			$result = $oCrypt->decrypt3DES ( $result );
			
			$mail = new Simplemail ();
			$mail->addFrom ( Pelican::$config ["MAIL_MPD_FROM"] );
			$mail->addrecipient ( $subscriber_email );
			$mail->addsubject ( t ( 'PEL.MDP.FORGOT_PASSWORD.SEND.SUBJECT' ) );
			
			$html = t ( 'PEL.MDP.SEND.HTML' ) . $result;
			$text = t ( 'PEL.MDP.SEND.TXT' ) . $result;
			
			$mail->html = $html;
			$mail->text = $text;
			
			if ($mail->sendmail ()) {
				return 1;
			} else {
				return 3;
			}
		} else {
			return 2;
		}
	}
	
	/**
	 * Fonction que va sauvegarder la dernière connexion de l'inscrit
	 *
	 * @access public
	 * @param $subscriber_id __TYPE__
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function subscriber_update_infos_connection($subscriber_id) {
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind = array ();
		$aBind [":SUBSCRIBER_LAST_IP"] = $_SERVER ["REMOTE_ADDR"];
		$aBind [":SUBSCRIBER_LAST_CONNECTION"] = date ( "Y-m-d:H:i:s", $_SERVER ["REQUEST_TIME"] );
		$aBind [":SUBSCRIBER_ID"] = $subscriber_id;
		$aBind [":SUBSCRIBER_NUMBER_CONNECTION"] = "SUBSCRIBER_NUMBER_CONNECTION+1";
		$aBind [":SITE_ID"] = Pelican::$config ['SITE_ID'];
		
		$sSql = "... requête ...";
		
		$oConnection->query ( $sSql, $aBind );
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $email __TYPE__
	 *       	 __DESC__
	 * @param $site_id __TYPE__
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function subscriber_exist_by_mail($email, $site_id) {
		
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind = array ();
		$aBind [":EMAIL"] = $oConnection->strToBind ( strtolower ( $email ) );
		$aBind [":SITE_ID"] = $site_id;
		
		$strSQL = " SELECT     *
	                        FROM     #pref#_subscriber 
	                        WHERE     LOWER(SUBSCRIBER_EMAIL) = :EMAIL
	                        AND     SITE_ID = :SITE_ID";
		$aResult = $oConnection->queryRow ( $strSQL, $aBind );
		
		if (is_array ( $aResult ) && sizeof ( $aResult ) > 0) {
			return $aResult;
		} else {
			return false;
		}
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $nickname __TYPE__
	 *       	 __DESC__
	 * @param $site_id __TYPE__
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public function subscriber_exist_by_nickname($nickname, $site_id) {
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind = array ();
		$aBind [":NICKNAME"] = strtolower ( $nickname );
		$aBind [":SITE_ID"] = $site_id;
		$strSQL = " SELECT     SUBSCRIBER_EMAIL, SUBSCRIBER_PASSWORD
	                        FROM     #pref#_subscriber 
	                        WHERE     LOWER(SUBSCRIBER_NICKNAME) = :NICKNAME 
	                        AND     SITE_ID = :SITE_ID";
		$aResult = $oConnection->queryItem ( $strSQL, $aBind );
		
		if (is_array ( $aResult ) && sizeof ( $aResult ) > 0) {
			return $aResult;
		} else {
			return false;
		}
	}
	
	/**
	 * __DESC__
	 *
	 * @static
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 * @access public
	 * @param $email __TYPE__
	 *       	 __DESC__
	 * @param $firstname __TYPE__
	 *       	 __DESC__
	 * @param $lastname __TYPE__
	 *       	 __DESC__
	 * @param $lien __TYPE__
	 *       	 __DESC__
	 * @param $site_id __TYPE__
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	public static function subscriber_send_inscription_confirm($email, $firstname, $lastname, $lien, $site_id) {
		
		require_once (pelican_path ( 'External.Simplemail' ));
		
		$mail = new Simplemail ();
		$mail->addrecipient ( $email );
		$mail->addFrom ( Pelican::$config ["MAIL_INSCRIPTION_FROM"] );
		$mail->addrecipient ( strtolower ( $Mail ) );
		$mail->addsubject ( t ( 'PEL.INSCRIPTION.MAIL_CONFIRM.SUBJECT' ) );
		
		$html = $firstname . " " . $lastname . ", " . t ( 'PEL.INSCRIPTION.MAIL_CONFIRM_1' ) . " <a href=" . $Lien . ">" . $lien . "</a><br/><br/>" . t ( 'PEL.INSCRIPTION.MAIL_CONFIRM_2' );
		$text = $firstname . " " . $lastname . t ( 'PEL.INSCRIPTION.MAIL_CONFIRM_1' ) . " <a href=" . $lien . ">" . $lien . "</a>" . t ( 'PEL.INSCRIPTION.MAIL_CONFIRM_2' );
		
		$mail->html = $html;
		$mail->text = $text;
		debug ( $mail );
		if ($mail->sendmail ()) {
			debug ( "ok" );
			exit ();
			return true;
		} else {
			debug ( "ko" );
			exit ();
			return false;
		}
	}
	
	/**
	 * Enter description here...
	 *
	 * @param $email unknown_type       	
	 * @param $password unknown_type       	
	 * @param $site_id unknown_type       	
	 * @return unknown
	 */
	
	public static function subscriber_exist_by_mail_password($email, $password, $site_id) {
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind = array ();
		$aBind [":SUBSCRIBER_EMAIL"] = $oConnection->strToBind ( $email );
		$aBind [":SUBSCRIBER_PASSWORD"] = $oConnection->strToBind ( $password );
		$aBind [":SITE_ID"] = $site_id;
		$strSQL = "
                    select 	SUBSCRIBER_ID
                    from    #pref#_subscriber 
                    where   SUBSCRIBER_EMAIL 	= :SUBSCRIBER_EMAIL
                    and		SUBSCRIBER_PASSWORD = :SUBSCRIBER_PASSWORD
                    and     SITE_ID 			= :SITE_ID
	            ";
		return $oConnection->queryItem ( $strSQL, $aBind );
	
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $email __TYPE__
	 *       	 __DESC__
	 * @param $site_id __TYPE__
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	static function subscriber_confirmation_inscription($email, $site_id) {
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind = array ();
		$aBind [":SUBSCRIBER_EMAIL"] = $oConnection->strToBind ( $email );
		$aBind [":SITE_ID"] = $site_id;
		$strSQL = "
                    update  #pref#_subscriber 
                    set     SUBSCRIBER_ONLINE = 1
                    where   SUBSCRIBER_EMAIL = :SUBSCRIBER_EMAIL
                    and     SITE_ID = :SITE_ID
	            ";
		$oConnection->query ( $strSQL, $aBind );
		
		return true;
	
	}
	
	/**
	 * __DESC__
	 *
	 * @access public
	 * @param $id __TYPE__
	 *       	 __DESC__
	 * @return __TYPE__
	 */
	static function subscriber_statut($id) {
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind = array ();
		$aBind [":SUBSCRIBER_ID"] = $id;
		$strSQL = "
                select 	SUBSCRIBER_ONLINE
                from    #pref#_subscriber 
                where   SUBSCRIBER_ID 	= :SUBSCRIBER_ID
            ";
		return $oConnection->queryItem ( $strSQL, $aBind );
	}
	
	/**
	 * Fonction insert / update pour l'enregistrement d'un inscrit
	 *
	 * @access public
	 * @return bool
	 */
	public function save() {
		
		$oConnection = Pelican_Db::getInstance ();
		
		$oCrypt = Pelican_Factory::getInstance ( 'Security.Crypt' );
		
		$aBind = array ();
		$aBind [":SUBSCRIBER_EMAIL"] = $oConnection->strToBind ( $this->getEmail () );
		$aBind [":CIVILITE_ID"] = $this->getCivilite ();
		$aBind [":SUBSCRIBER_NICKNAME"] = $oConnection->strToBind ( $this->getNickname () );
		$aBind [":SUBSCRIBER_FIRSTNAME"] = $oConnection->strToBind ( $this->getFirstname () );
		$aBind [":SUBSCRIBER_LASTNAME"] = $oConnection->strToBind ( $this->getLastname () );
		$aBind [":SUBSCRIBER_MOBILE_PHONE"] = $oConnection->strToBind ( $this->getMobilePhone () );
		$aBind [":SUBSCRIBER_ADDRESS"] = $oConnection->strToBind ( $this->getAddress () );
		$aBind [":SUBSCRIBER_ZIP_CODE"] = $oConnection->strToBind ( $this->getZipCode () );
		$aBind [":SUBSCRIBER_CITY"] = $oConnection->strToBind ( $this->getCity () );
		$aBind [":SITE_ID"] = Pelican::$config ["SITE"] ["ID"];
		$aBind [":SUBSCRIBER_PASSWORD"] = $oConnection->strToBind ( $oCrypt->encrypt3DES ( $this->getPassword () ) );
		
		if ($this->getId () > 0 && $this->getId () != "") {
			
			$aBind [":SUBSCRIBER_ID"] = $this->getId ();
			
			$strSQL = "
	                            update #pref#_subscriber 
	                            set
	                                    SUBSCRIBER_EMAIL             = :SUBSCRIBER_EMAIL,
	                                    SUBSCRIBER_PASSWORD         = :SUBSCRIBER_PASSWORD,
	                                    SUBSCRIBER_FIRSTNAME         = :SUBSCRIBER_FIRSTNAME,
	                                    SUBSCRIBER_LASTNAME         = :SUBSCRIBER_LASTNAME,
	                                    SUBSCRIBER_ADDRESS             = :SUBSCRIBER_ADDRESS,
	                                    SUBSCRIBER_ZIP_CODE         = :SUBSCRIBER_ZIP_CODE,
	                                    SUBSCRIBER_NICKNAME         = :SUBSCRIBER_NICKNAME,
	                                    SUBSCRIBER_CITY             = :SUBSCRIBER_CITY 
	                            where     SUBSCRIBER_ID                 = :SUBSCRIBER_ID
	                        ";
		} else {
			$strSQL = "
	                            insert into #pref#_subscriber
	                                     (
	                                     SUBSCRIBER_EMAIL, 
	                                     SUBSCRIBER_PASSWORD,
	                                    SUBSCRIBER_FIRSTNAME,
	                                    SUBSCRIBER_LASTNAME,
	                                    SUBSCRIBER_ADDRESS,
	                                    SUBSCRIBER_ZIP_CODE,
	                                    SUBSCRIBER_NICKNAME,
	                                    SUBSCRIBER_CITY,
	                                    SUBSCRIBER_DATE,
	                                    SITE_ID
	                                    )
	                            values    (
	                                    :SUBSCRIBER_EMAIL, 
	                                     :SUBSCRIBER_PASSWORD,
	                                    :SUBSCRIBER_FIRSTNAME,
	                                    :SUBSCRIBER_LASTNAME,
	                                    :SUBSCRIBER_ADDRESS,
	                                    :SUBSCRIBER_ZIP_CODE,
	                                    :SUBSCRIBER_NICKNAME,
	                                    :SUBSCRIBER_CITY,
	                                    now(),
	                                    :SITE_ID
	                                    )
	                        ";
		}
		
		$this->setPassword ( $aBind [":SUBSCRIBER_PASSWORD"] );
		
		$oConnection->query ( $strSQL, $aBind );
		
		if (! $this->getId ()) {
			require_once (pelican_path ( 'User.Service' ));
			$subscriber_id = $oConnection->queryItem ( "SELECT MAX(SUBSCRIBER_ID) FROM #pref#_subscriber" );
			$oServices = new Service ();
			$aService = $oServices->service_all ( Pelican::$config ["SITE"] ["ID"], "", "defaut" );
			if ($aService) {
				foreach ( $aService as $serv ) {
					$this->serviceSubscribe ( $subscriber_id, $serv ["SERVICE_ID"] );
				}
			}
		
		}
		/*
		 * test a rajouter au cas ou la requete se passe pas bien
		 */
		return true;
	}
	
	/**
	 * Fonction insert l'abonnement à un service d'un inscrit
	 *
	 * @access public
	 * @return bool
	 */
	public function serviceSubscribe($subscriber_id, $service_id) {
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind [":SUBSCRIBER_ID"] = $subscriber_id;
		$aBind [":SERVICE_ID"] = $service_id;
		
		$verif = $oConnection->queryItem ( "SELECT 1 FROM #pref#_subscription WHERE service_id = :SERVICE_ID AND subscriber_id = :SUBSCRIBER_ID", $aBind );
		
		if (! $verif) {
			$aBind [":SUBSCRIPTION_ENABLED"] = "1";
			$strSql = "	INSERT INTO #pref#_subscription 
						(
							SUBSCRIBER_ID,
							SERVICE_ID,
							SUBSCRIPTION_DATE,
							SUBSCRIPTION_ENABLED
						) VALUES (
							:SUBSCRIBER_ID,
							:SERVICE_ID,
							now(),
							:SUBSCRIPTION_ENABLED
						)";
			$oConnection->query ( $strSql, $aBind );
		}
	
	}
	public function serviceSubscribeFO($subscriber_id, $aServices) {
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind [":SUBSCRIBER_ID"] = $subscriber_id;
		
		$sqlDelete = "DELETE FROM #pref#_subscription 
						WHERE subscriber_id = :SUBSCRIBER_ID
						AND service_id in (SELECT service_id FROM #pref#_service WHERE service_subscription = 1)";
		
		$oConnection->query ( $sqlDelete, $aBind );
		
		$aBind [":SUBSCRIPTION_ENABLED"] = "1";
		$strSql = "	INSERT INTO #pref#_subscription 
						(
							SUBSCRIBER_ID,
							SERVICE_ID,
							SUBSCRIPTION_DATE,
							SUBSCRIPTION_ENABLED
						) VALUES (
							:SUBSCRIBER_ID,
							:SERVICE_ID,
							now(),
							:SUBSCRIPTION_ENABLED
						)";
		foreach ( $aServices as $key => $val ) {
			$aBind [":SERVICE_ID"] = $val;
			$oConnection->query ( $strSql, $aBind );
		}
	
	}
}
?>