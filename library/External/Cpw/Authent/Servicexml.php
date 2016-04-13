<?php
require_once 'Service.php';
/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Base class for implementing connection from a XML file
 *
 * @category  Cpw
 * @package   Cpw_Authent
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA 
 */

class Cpw_Authent_Servicexml  extends Cpw_Authent_Service 
{
	private $_users;
    /**
    * Function to initialize and load Xml Data File.
    * @param string $pathfile
    */
    public function __construct($pathfile = '')
    {
    	if (file_exists($pathfile)) 
    		$this->_users = simplexml_load_file($pathfile);
    }
    
    /**
     *  Function to destruction Connection to Xml data file.
     */
    public function __destruct() {
        unset($this->_users);
    }

    /**
     * Function which search and return an array of User's Object if found, else an error code.
	 * @param string $Uid
     * @return array['ErrorCode'=>$CodeError, 'Data'=>$User]
     */
    private function findUserByUid($Uid)
    {
    	if (is_null($this->_users))
    	{
    		$result['ErrorCode'] = CPW_DIRECTORY_XMLFILE_ERROR;
    		return $result;
    	}
    	foreach ($this->_users->user as $user)
    	{
    		if ($user['login'] == $Uid)
    		{
    			$result['ErrorCode'] = CPW_DIRECTORY_NO_ERROR;
    			$result['Data'] = $user;
    			return $result;
    		}
    	}
    	$result['ErrorCode'] = USER_NOT_FOUND;
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::getUserFirstName()
     */
    public function getUserFirstName($pUser)
    {
    	return $pUser->firstname;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::getUserLastName()
     */
    public function getUserLastName($pUser)
    {
    	return $pUser->lastname;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::getUserEmail()
     */
    public function getUserEmail($pUser)
    {
    	return $pUser->email;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::getUserCountry()
     */
    public function getUserCountry($pUser)
    {
    	return $pUser->country;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::getUserLocale()
     */
    public function getUserLocale($pUser)
    {
    	return $pUser->locale;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::getUserTitle()
     */
    public function getUserTitle($pUser)
    {
    	return $pUser->title;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::getUserGroups()
     */
    public function getUserGroups($pUser)
    {
        $groups = array();
        foreach ($pUser->groups as $group)
        {
        	foreach ($group as $grp)
        	{
        		$groups[]= $grp->name;
        	}
        }
        return $groups;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::authenticateUser()
     */
    public function authenticateUser($uid, $password)
    {
    	$result['ErrorCode'] = CPW_DIRECTORY_NO_ERROR;
        $result = $this->findUserByUid($uid);
        if ($result['ErrorCode'] > CPW_DIRECTORY_NO_ERROR) return $result;
        $user = $result['Data'];
        if ($user['password'] != $password)
        {
            $result['ErrorCode'] =521;
        }
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see Cpw_Authent_Service::freeUser()
     */
    public function freeUser($pUser)
    {
        unset($pUser);
    }
}
?>