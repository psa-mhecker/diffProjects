<?php
// ================================================
// =========== DRAFT MANAGEMENT ===================
// ================================================


include_once("config.php");

class DraftUtil
{
	// checks if a draft exists
    public static function checkDraft ($brand, $country, $culture, $component)
    {
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$aBind[':DRAFT_COMPOSANT_BRAND']     = $oConnection->strToBind($brand);
    	$aBind[':DRAFT_COMPOSANT_COUNTRY']   = $oConnection->strToBind($country);
    	$aBind[':DRAFT_COMPOSANT_CULTURE']   = $oConnection->strToBind($culture);
    	$aBind[':DRAFT_COMPOSANT_COMPONENT'] = $oConnection->strToBind($component);
    	
    	$Draft = $oConnection->queryItem("select count(*) as nb from #pref#_boforms_composant_draft where 
    									    DRAFT_COMPOSANT_BRAND = :DRAFT_COMPOSANT_BRAND and
											DRAFT_COMPOSANT_COUNTRY = :DRAFT_COMPOSANT_COUNTRY and 
											DRAFT_COMPOSANT_CULTURE = :DRAFT_COMPOSANT_CULTURE and
											DRAFT_COMPOSANT_COMPONENT = :DRAFT_COMPOSANT_COMPONENT", $aBind);
    	    	
    	$result = ($Draft['nb'] > 0) ? true : false;
    	return $result;
    }
	
	
	// loads a draft
    public static function getDraft($brand, $country, $culture, $component)
    {
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$aBind[':DRAFT_COMPOSANT_BRAND']     = $oConnection->strToBind($brand);
    	$aBind[':DRAFT_COMPOSANT_COUNTRY']   = $oConnection->strToBind($country);
    	$aBind[':DRAFT_COMPOSANT_CULTURE']   = $oConnection->strToBind($culture);
    	$aBind[':DRAFT_COMPOSANT_COMPONENT'] = $oConnection->strToBind($component);
    	
    	$aDraft = $oConnection->queryTab("select DRAFT_COMPOSANT_JSON 
    									  from #pref#_boforms_composant_draft where 
											DRAFT_COMPOSANT_BRAND = :DRAFT_COMPOSANT_BRAND and 
											DRAFT_COMPOSANT_COUNTRY = :DRAFT_COMPOSANT_COUNTRY and
											DRAFT_COMPOSANT_CULTURE = :DRAFT_COMPOSANT_CULTURE and
											DRAFT_COMPOSANT_COMPONENT = :DRAFT_COMPOSANT_COMPONENT", $aBind);
    	
    	$result = (!empty($aDraft)) ? json_decode($aDraft[0]['DRAFT_COMPOSANT_JSON']) : null;
    	return $result;
    }

	// deletes a draft
    public static function deleteDraft($brand, $country, $culture, $component)
    {
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$aBind[':DRAFT_COMPOSANT_BRAND']     = $oConnection->strToBind($brand);
    	$aBind[':DRAFT_COMPOSANT_COUNTRY']   = $oConnection->strToBind($country);
    	$aBind[':DRAFT_COMPOSANT_CULTURE']   = $oConnection->strToBind($culture);
    	$aBind[':DRAFT_COMPOSANT_COMPONENT'] = $oConnection->strToBind($component);
    	
    	$oConnection->query("delete from #pref#_boforms_composant_draft where 
											DRAFT_COMPOSANT_BRAND = :DRAFT_COMPOSANT_BRAND and 
											DRAFT_COMPOSANT_COUNTRY = :DRAFT_COMPOSANT_COUNTRY and 
											DRAFT_COMPOSANT_CULTURE = :DRAFT_COMPOSANT_CULTURE and
											DRAFT_COMPOSANT_COMPONENT = :DRAFT_COMPOSANT_COMPONENT", $aBind);
    	
    	return true;
    }
    
	public static function createDraft($brand, $country, $culture, $component, $json)
    {
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$aBind[':DRAFT_COMPOSANT_BRAND']     = $oConnection->strToBind($brand);
    	$aBind[':DRAFT_COMPOSANT_COUNTRY']   = $oConnection->strToBind($country);
    	$aBind[':DRAFT_COMPOSANT_CULTURE']   = $oConnection->strToBind($culture);
    	$aBind[':DRAFT_COMPOSANT_COMPONENT'] = $oConnection->strToBind($component);
    	$aBind[':DRAFT_COMPOSANT_JSON']      = $oConnection->strToBind($json);
    	
    	$oConnection->query("insert into #pref#_boforms_composant_draft values(:DRAFT_COMPOSANT_BRAND, 
    																		   :DRAFT_COMPOSANT_COUNTRY,
    																		   :DRAFT_COMPOSANT_CULTURE,
    																		   :DRAFT_COMPOSANT_COMPONENT,
    																		   :DRAFT_COMPOSANT_JSON)", $aBind);
    	
    	return true;
    }
	
}