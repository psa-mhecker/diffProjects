<?php

/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * class which transform datas 
 * - tranformation from Customer weberservice datas to an Array
 * - tranformation from an Array to Customer weberservice datas
 *
 * @category  Cpw
 * @package   Cpw
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */

class Cpw_Usermanager
{
    
	/**
	 * Return a complex array of user data's (Customer Webservice Structure) based on an user's array.
	 * @param array $userdatas
	 * @param array $datas
	 * @return complex array 
	 */
    public function GetCustomerDatas($userdatas, $datas = array())
    {
    	$arrdatas = $datas;
    	foreach($userdatas as $key=>$data)
    	{
    		if(defined('Cpw_GRCOnline_Customerfields::'.$key))
    		{
    			$property = array(
		    		'PropertyCode' => $key,
		    		'PropertyValue' => $data,
		    		'PropertyCulture' => 'fr-FR',
		    		'PropertyStatus' => 'OK',
		    		'IsReferenced' => '0',
		    		'IsTrusted'	 => '0'
		    	);
    			
    			$exist=false;
    			foreach($arrdatas as $_key => $_data)
    			{
    				if (($_data['PropertyCode']==$key))
    				{
    					$arrdatas[$_key] = $property;
    					$exist=true;
    					break;
    				}
    			}
    			if (!$exist) $arrdatas[] = $property;
    		}
    	}
    	return $arrdatas;
    }
    
    /**
     * Return an array (key/value) of user informations based on data's customer webservice structure
     * @param array $userdatas
     * @param array $datas
     * @return array('USR_ADDR_1'=>'Adresse1',  'USR_ADDR_2'=>'Adresse2');
     */
    public function GetUserDatas($userdatas, $datas)
    {
    	foreach($datas as $key=>$data)
    	{
    		if(defined('Cpw_GRCOnline_Customerfields::'.$key))
    		{
    			$userdatas[$key] = $data;
    		}
    	}
    	return $userdatas;
    }
    
}
?>