<?php
require_once 'Authent/Servicexml.php';
require_once 'Authent/Serviceldap.php';
/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Base class that represent a CPPv2 "CPW" back-office PSA user.
 *
 * @category  Cpw
 * @package   Cpw
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
define('CPW_DIRECTORY_NO_ERROR', 					0);    // no error
define('CPW_DIRECTORY_SECTION_NOT_FOUND', 			1000); // application.ini miss the config section
define('CPW_DIRECTORY_SECTION_TYPE_NOT_FOUND',		1001); // application.ini miss the directory.type section
define('CPW_DIRECTORY_SECTION_FILEPATH_NOT_FOUND',	1002); // application.ini miss the directory.filepath section
define('CPW_DIRECTORY_TYPE_ERROR',					1003); // application.ini directory.type section invalid
define('CPW_DIRECTORY_FILEPATH_OR_TYPE_ERROR',		1004); // application.ini directory.filepath section invalid
define('CPW_DIRECTORY_XMLFILE_ERROR', 				1005); // mock xml file is not well formed

if (!defined('USER_NOT_FOUND'))
{
	/* In case the LDAPC library is not loaded, override the const
	 * to make the XML wrapper compatible
	 *
	 */
	define('USER_NOT_FOUND', 518);
}

class Cpw_User
{
	const CPW_REGEX_ROLES_AUTRES 	=	'#^(CPW\.(ADMINISTRATEUR|WEBMASTER|CONTRIBUTEUR|IMPORTATEUR)\.)#';
	const CPW_ROLES_ADMIN     		=	'CPW.ADMINISTRATEUR';
	const ROLE_ADMINISTRATEUR 		=	'ADMINISTRATEUR';
	const ROLE_WEBMASTER      		=	'WEBMASTER';
	const ROLE_CONTRIBUTEUR   		=	'CONTRIBUTEUR';
	const ROLE_IMPORTATEUR    		=   'IMPORTATEUR';
	// Droits d'accès métiers
	const ROLE_ACCESSOIRES		= 'ACC';
	const ROLE_APV				= 'APV';
	const ROLE_BUSINESS			= 'BUSINESS';
	const ROLE_COMMUNICATION	= 'COMM';
	const ROLE_FINANCING		= 'FIN';
	const ROLE_MARCHES			= 'MARCHES';
	const ROLE_MKTCOMM			= 'MKTCOMM';
	const ROLE_MKTINTERNET		= 'MKTINTERNET';
	const ROLE_PRODUITS			= 'PRODUITS';
	const ROLE_RH				= 'RH';
	const ROLE_RLC				= 'RLC';
	const ROLE_VN				= 'VN';
	const ROLE_VO				= 'VO';

	private $_RightsLevel =array(
    	self::ROLE_ADMINISTRATEUR 	=> 	4,
		self::ROLE_WEBMASTER 		=> 	3,
		self::ROLE_CONTRIBUTEUR 	=> 	2,
		self::ROLE_IMPORTATEUR 		=> 	1,
    	);
	private $_BusinessLst =array(self::ROLE_ACCESSOIRES, self::ROLE_APV,
			self::ROLE_BUSINESS, self::ROLE_COMMUNICATION,
			self::ROLE_COMMUNICATION, self::ROLE_FINANCING,
			self::ROLE_MARCHES, self::ROLE_MKTCOMM,
			self::ROLE_MKTINTERNET, self::ROLE_PRODUITS,
			self::ROLE_RH, self::ROLE_RLC,
			self::ROLE_VN, self::ROLE_VO,
	);
	private $_password;
	protected $_isAdmin 	= false;
	protected $_rights 		= array();
	protected $_business 	= array();
    protected $_lastname;
    protected $_firstname;
    protected $_login;
    protected $_locale;
    protected $_country;
    protected $_title;
    protected $_email;
    protected $_errorcode;
    protected $_errorstring;

    /**
     * Construtor of the Cpw_User class
     * @param string $login
     * @param string $password
     */
    public function __construct($login, $password)
    {
    	$this->_login = $login;
    	$this->_password = $password;
    }

    /**
     * Free the ressource of the object Cpw_User
     */
    function logout()
    {
    	unset($this->_login);
    	unset($this->_password);
    }


    /**
     * Return an object of the authent service type, as it is configured in the .ini file.
     * @see Cpw_Authent_Serviceldap
     * @see Cpw_Authent_Servicexml
     * @return false, or class Cpw_Authent_Serviceldap or class Cpw_Authent_Servicexml
     */
    public function getServiceType()
    {

    	$frontController = Zend_Controller_Front::getInstance();
    	$directorySrv  = Pelican:: $config['DIRECTORY_LDAP'];
    	if (is_null($directorySrv))
    	{
    		$this->_errorcode = CPW_DIRECTORY_SECTION_NOT_FOUND;
    		return false;
    	}
    	if (!isset($directorySrv['type']))
    	{
    		$this->_errorcode = CPW_DIRECTORY_SECTION_TYPE_NOT_FOUND;
    		return false;
    	}
    	if (!isset($directorySrv['filepath']))
    	{
    		$this->_errorcode = CPW_DIRECTORY_SECTION_FILEPATH_NOT_FOUND;
    		return false;
    	}
    	if ($directorySrv['filepath'] == '' || $directorySrv['type'] == '' )
    	{
    		$this->_errorcode = CPW_DIRECTORY_FILEPATH_OR_TYPE_ERROR;
    		return false;
    	}

    	$filePath = $directorySrv['filepath'];
    	switch ($directorySrv['type'])
    	{
    		case 'xml':
    			return new Cpw_Authent_Servicexml($filePath);
    			break;
    		case 'ldap':
    			return new Cpw_Authent_Serviceldap($filePath);
    			break;
    		default :
    			$this->_errorcode = CPW_DIRECTORY_TYPE_ERROR;
    			return false;
    			break;
    	}
    }


	/**
     * Check and initialize the Cpw_User's object.
     * If ini failed, check $this->_errorcode and $this->errorstring to see the error
     * @return bool true if init ok ; false if init failed
     */
    function login()
    {
    	$LdapSrv = $this->getServiceType();
    	if ($LdapSrv === false)	return false;
    	$result = $LdapSrv->authenticateUser($this->_login, $this->_password);
    	$user 	= $result['Data'];
    	if ($result['ErrorCode'] == CPW_DIRECTORY_NO_ERROR)
    	{
    		$this->_firstname 	= 	$LdapSrv->getUserFirstName($user);
    		$this->_lastname 	= 	$LdapSrv->getUserLastName($user);
    		$this->_email 		= 	$LdapSrv->getUserEmail($user);
    		$this->_country 	= 	$LdapSrv->getUserCountry($user);
    		$this->_title 		= 	$LdapSrv->getUserTitle($user);
    		$this->_locale 		= 	$LdapSrv->getUserLocale($user);
    		$this->setRightsBusiness($LdapSrv->getUserGroups($user));
    		$LdapSrv->freeUser($user);
    		return true;
    	}
    	else
    	{
    		$this->_errorcode 	= $result['ErrorCode'];
    		$this->_errorstring = $LdapSrv->errorCodeToConstName($result['ErrorCode']) ;
    	}
    	return false;
    }

    /**
     * Build an array of the higher rights by country => Array('BE'=>'WEBMASTER','FR'=>'CONTRIBUTEUR');
     * Build an array of the business rights by country => Array('BE'=>Array(0 => Array('WEBMASTER'=>'MKTINTERNET'), 1 => Array('CONTRIBUTEUR'=>'MKTINTERNET')));
     * @param array[string] $grps
     * @return nothing
     */
    private function setRightsBusiness($grps)
    {
    	if  (in_array(self::CPW_ROLES_ADMIN, $grps))	$this->_isAdmin = true;

    	$grps = preg_grep(self::CPW_REGEX_ROLES_AUTRES, $grps);
    	foreach ($grps as $grp)
    	{
    		$datas = preg_split('#\.#', $grp);
    		if (count($datas) === 3)
    		{
    			$role 	= $datas[1];
    			$countryBusiness= $datas[2];
    			$country =$countryBusiness;
    			if (strlen($country) > 2) $country = substr($countryBusiness, 0,2);
    			if (strlen($country)==2)
    			{
    				$Business = '';
    				if (strlen($countryBusiness) > 2)
    					$Business = substr($countryBusiness, strlen($country));
	    			if (isset($this->_rights[$country]))
	    			{
	    				if ($this->_RightsLevel[$role] > $this->_RightsLevel[$this->_rights[$country]])
	    				{
	    					$this->_rights[$country] = $role;
	    				}
	    			}
	    			else
	    			{
	    				$this->_rights[$country] = $role;
	    			}

	    			if ($this->CheckBusinessRole($Business) || $Business =='')
	    				$this->_business[$country][$role.$Business] = array( $role=> $Business);
    			}
    		}
    	}
    }

    /**
     * Compare and check a role to the authorized BusinessRole list .
     * @param string $role
     * @return boolean
     */
    private function CheckBusinessRole($role)
    {
    	foreach($this->_BusinessLst as $business)
    	{
    		if (strtoupper($role) == strtoupper($business)) return true;
    	}
    	return false;
    }

    /**
     * Get list of higher rights by country.
     * This list in an array : key is the country code, value is a string
     * representing the rights.
     * Value can be any of the defined class constants Cpw_User::ROLE_xxx, like
     * ROLE_ADMINISTRATEUR (string "ADMINISTRATEUR")
     * ROLE_cONTRIBUTEUR (string "CONTRIBUTEUR")
     * ROLE_WEBMASTER (string "WEBMASTER")
     * ROLE_IMPORTATEUR (string "IMPORTATEUR")
     *
     * @see country code : http://fr.wikipedia.org/wiki/ISO_3166-2
     *
     * @return  Array('BE'=>Cpw_User::ROLE_ADMINISTRATEUR,'FR'=>Cpw_User::ROLE_WEBMASTER, ...)
     */
    public function getRights()
    {
    	return $this->_rights;
    }

    /**
     * Get list of allowed business by country for the current user.
     * This list is an array : key is the country code, value is an array of string that contains
     * the business identifier.
     * Business identifier can be found in class constants Cpw_User::ROLE_xxx like
     * ROLE_FIN (string "FIN")
     * ROLE_APVN (string "APV")
     *
     * @return Array('BE'=>Array(
     * 				0 => Array(Cpw_User::ROLE_CONTRIBUTEUR => Cpw_User::ROLE_COMMUNICATION),
     * 				1 => Array(Cpw_User::ROLE_IMPORTATEUR =>Cpw_User::ROLE_MKTINTERNET)));
     */
    public function getBusiness()
    {
    	return $this->_business;
    }

    /**
     * return true if the user is an Administrator.
     * The Admin profile must have access to all the features
     * @return boolean
     */
    public function isAdmin()
    {
    	return $this->_isAdmin;
    }

    /**
     * Get the UID of the User
     * @return string
     */
    public function getLogin()
    {
    	return $this->_login;
    }

    /**
     * Get the LastName of the User
     * @return string
     */
    public function getLastname()
    {
        return $this->_lastname;
    }

    /**
     * Get the Firstname of the User
     * @return string
     */
    public function getFirstname()
    {
    	return $this->_firstname;
    }

    /**
     * Get the Email of the User
     * @return string
     */
    public function getEmail()
    {
    	return $this->_email;
    }

    /**
     * Get the Country of the User , Format code ISO 2, Exemple : "FR"
     * @see http://fr.wikipedia.org/wiki/ISO_3166-2
     * @return string
     */
    public function getCountry()
    {
    	return $this->_country;
    }

    /**
     * Get the Title of the User, Exemple : "Mr". The title is localized (it is not a code)
     * @return string
     */
    public function getTitle()
    {
    	return $this->_title;
    }

    /**
     * Get the Locale of the User, Format code ISO 2, Example : "FR"
     * @return string
     */
    public function getLocale()
    {
    	return $this->_locale;
    }

    /**
     * Get the Code of the last error.
     * Error is filled only if a function failed.
     * @see Service::errorCodeToConstName()
     * @return integer
     */
    public function getLastErrorCode()
    {
    	return $this->_errorcode;
    }

    /**
     * Get the Label of the last error
     * Error is filled only if a function failed.
     * @return string
     */
    public function getLastErrorString()
    {
    	return Cpw_Authent_Service::errorCodeToConstName($this->_errorcode);
    }
}
?>