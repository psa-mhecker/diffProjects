<?php
/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Absrtact class for manage authent and connexion to the CPW back office.
 * Must be implemented in a Cpw_Authent_Serviceldap or Cpw_Authent_Servicexml
 * 
 * Country code returned in this class are the same as the ISO-3166-2 codes, except 
 * for the "CT" code that indicates a virtual "Central / headquarter" country.
 * 
 * @see Cpw_Authent_Serviceldap
 * @see Cpw_Authent_Servicexml
 * @see http://fr.wikipedia.org/wiki/ISO_3166-2
 *
 * @category  Cpw
 * @package   Cpw_Authent
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA 
 */
abstract class Cpw_Authent_Service
{
	const MAX_GROUP = 2000;

    /**
     * Convert an error code to a constant name
     * @param integer $errCode error code return by getLdapErrorCode
     * @return string ('LDAP_NO_ERROR', 'LDAP_CPW_UNKNOWN_ERROR_CODE' or an other const name defined in the CLP library
     */
    static public function errorCodeToConstName($errCode)
    {
    	global $LDAP_CPW_ALL_CONSTS;
    	if (!isset($LDAP_CPW_ALL_CONSTS))
    	{    		
    		$LDAP_CPW_ALL_CONSTS[0] = 'LDAP_NO_ERROR';
    		$tmp 					= get_defined_constants(true);
    		if (isset($tmp['ldapc']))
    		{
    			$tmp = $tmp['ldapc']; 
	    		foreach($tmp as $constname => $constvalue)
	    		{
	    			if ($constvalue > 255)
	    			{
	    				$LDAP_CPW_ALL_CONSTS[$constvalue] = $constname;
	    			}
	    		}
    		}
    		$LDAP_CPW_ALL_CONSTS[CPW_DIRECTORY_SECTION_NOT_FOUND] 			= 'CPW_DIRECTORY_SECTION_NOT_FOUND';
    		$LDAP_CPW_ALL_CONSTS[CPW_DIRECTORY_SECTION_TYPE_NOT_FOUND] 		= 'CPW_DIRECTORY_SECTION_TYPE_NOT_FOUND';
    		$LDAP_CPW_ALL_CONSTS[CPW_DIRECTORY_SECTION_FILEPATH_NOT_FOUND] 	= 'CPW_DIRECTORY_SECTION_FILEPATH_NOT_FOUND';
    		$LDAP_CPW_ALL_CONSTS[CPW_DIRECTORY_TYPE_ERROR] 					= 'CPW_DIRECTORY_TYPE_ERROR';
    		$LDAP_CPW_ALL_CONSTS[CPW_DIRECTORY_FILEPATH_OR_TYPE_ERROR] 		= 'CPW_DIRECTORY_FILEPATH_OR_TYPE_ERROR';
    		$LDAP_CPW_ALL_CONSTS[CPW_DIRECTORY_XMLFILE_ERROR] 				= 'CPW_DIRECTORY_XMLFILE_ERROR';
    	}
    	
    	if (!isset($LDAP_CPW_ALL_CONSTS[$errCode]))
    		return 'LDAP_CPW_UNKNOWN_ERROR_CODE';
   		else
    		return $LDAP_CPW_ALL_CONSTS[$errCode];
    }

    /**
     * @return string, Firstname of user'object, Format String, Example : "Dupont"
     * @param UserObject $pUser
     */
    abstract public function getUserFirstName($pUser);
    
    /**
     * @return string, LastName of user's object, Format String, Example : "John"
     * @param UserObject $pUser
     */
    abstract public function getUserLastName($pUser);
    
    /**
     * @return string, the Email of the User, Format String, Example : "user@domaine.com"
     * @param UserObject $pUser
     */
    abstract public function getUserEmail($pUser);
    
    /**
     * @return string, the Country of the User, Format code ISO 2,Example : "FR"
     * @param UserObject $pUser
     */
    abstract public function getUserCountry($pUser);
    
    /**
     * @return string, the Locale of the User, Format code ISO 2, Example : "FR"
     * @param UserObject $pUser
     */
    abstract public function getUserLocale($pUser);
    
    /**
     * @return string, the Title of the User, Example : "Mr"
     * @param UserObject $pUser
     */
    abstract public function getUserTitle($pUser);

    /**
     * return list of the user's rigths
     * @return array('CPW.WEBMASTER.FR','CPW.CONTRIBUTEUR.ES');
     * @param UserObject $pUser
     */
    abstract public function getUserGroups($pUser);
    
    /**
     * Check in Ldap Directory the user information UID and Password.
     * @return array('ErrorCode'=>CodeNumber, 'Data' => UserObject);
     * @param string $uid
     * @param string $password
     */
    abstract public function authenticateUser($uid, $password);
    
    /**
     * free ressources of the object
     * @param UserObject $pUser
     */
    abstract public function freeUser($pUser);
}
?>