<?php

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
 * @package   Cpw_GRCOnline_CustomerAt
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */


class Cpw_GRCOnline_CustomerAt_Dealer
{
	public $LatLong ;
	public $Town ;
	public $Adress1 ;
	public $Adress2 ;
	public $PostCode ;
	public $Country ;
	public $Brand ;
	public $CountryCode ;
	public $Name ;
	public $Type; //relatedgeositepreferredvn ou relatedgeositepreferreapv
	public $customerMng;
	
	/**
     * Construtor of the Cpw_GRCOnline_CustomerAt_Dealer class
     */
    public function __construct($datas = null)
    {
    	$this->customerMng 	= new Cpw_GRCOnline_Customermanager(PUBLIC_PATH.'/Wsdl/CRMDirect.wsdl');
    	if ($datas != null)
    	{
	    	foreach($datas as $key => $data)
	    	{
	    		switch ($key) 
	    		{
	    			case Cpw_GRCOnline_Customerfields::DLR_LATLONG:
	    				$this->LatLong = $data;
	    				break;
	    			case Cpw_GRCOnline_Customerfields::DLR_TOWN:
	    				$this->Town = $data;
	    				break;
	    			case Cpw_GRCOnline_Customerfields::DLR_ADR1:
	    				$this->Adress1 = $data;
	    				break;
	    			case Cpw_GRCOnline_Customerfields::DLR_ADR2:
	    				$this->Adress2 = $data;
	    				break;
	    			case Cpw_GRCOnline_Customerfields::DLR_COUNTRY:
	    				$this->Country = $data;
	    				break;
	    			case Cpw_GRCOnline_Customerfields::DLR_POSTCODE:
	    				$this->PostCode = $data;
	    				break;
	    			case Cpw_GRCOnline_Customerfields::DLR_COUNTRYCODE:
	    				$this->CountryCode = $data;
	    				break;
	    			case Cpw_GRCOnline_Customerfields::DLR_NAME:
	    				$this->Name = $data;
	    				break;
    				case 'REL_PREFERED_DEALER_TYPE':
    					$this->Type = $data;
    					break;
	    		}
	    	}
    	}
    }
    
    public function getArrayValues()
    {
    	$arrvalues = array();
    	if ($this->Town != '')	$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_TOWN, 'value'	=> $this->Town);
    	if ($this->Adress1 != '')	$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_ADR1, 'value'	=> $this->Adress1);
    	if ($this->Adress2 != '')	$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_ADR2, 'value'	=> $this->Adress2);
    	if ($this->PostCode != '')$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_POSTCODE, 'value'	=> $this->PostCode);
    	if ($this->Brand != '')	$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_BRAND, 'value'	=> $this->Brand);
    	if ($this->CountryCode != '')	$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_COUNTRYCODE, 'value'	=> $this->CountryCode);
    	if ($this->Country != '')	$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_COUNTRY, 'value'	=> $this->Country);
    	if ($this->LatLong != '')	$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_LATLONG, 'value'	=> $this->LatLong);
    	if ($this->Name != '')	$arrvalues[] = array('codesi'	=> Cpw_GRCOnline_Customerfields::DLR_NAME, 'value'	=> $this->Name);
    	return $arrvalues;
    }
    
	public function addDealer($accesstoken)
    {
    	
 		$instance = array(
			'node' => array(
				'name' => 'geosites',
				'element' => array('name' => 'geosite', 'data' => $this->getArrayValues())
			)
 		);

    	$this->customerMng->PushElement($instance, $accesstoken);
    	
    	if ($this->customerMng->onError()) 
    		return false;
    	return  $this->updateRelationDealer($accesstoken);
    }
    
    public function deleteDealer($accesstoken)
    {
    	
    	return  $this->updateRelationDealer($accesstoken, true);
    }
    
    protected function updateRelationDealer($accesstoken , $toRemove = false)
    {
    	$detail = array(
    			'name' => 'geosite',
    			'byCode' => 
    			array(
    				'codesi'	=> Cpw_GRCOnline_Customerfields::DLR_COUNTRYCODE,
    				'value'	=> $this->CountryCode
    				)
    			);
    	if ($toRemove)	
    	{
    		$detail = array(
    			'toremove'=> 'true',
    			'name' => 'geosite',
    			'byCode' => 
    			array(
    				'codesi'	=> Cpw_GRCOnline_Customerfields::DLR_COUNTRYCODE,
    				'value'	=> $this->CountryCode
    				)
    			);
    	}
    	
    	$instance = array(
    		'node' => 
    			array(
    				'name' => 'relatedgeosites',
    				'element' => 
    					array(
    						'name' => $this->Type,
    						'reference' => $detail
    						)
    				)
    	);
    	$this->customerMng->UpdateRelationShip($instance, $accesstoken);
    	if ($this->customerMng->onError())	return false;
    	return true;
    	
    }
    
    
}
?>