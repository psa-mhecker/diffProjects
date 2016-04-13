<?php

/**
 * @package Cache
 * @subpackage Config
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur WS Moteur de config
 *
 * retour : tableau data Moteur de config
 *
 * @package Cache
 * @subpackage Config
 */
class Backend_MoteurdeConfig extends Citroen_Cache {

    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
		
		$aComboLcdvMtcfg = $aVehicules = $aInfosVehicules =array();
		
      
        $serviceParams= array(
			'client' => 'CPPV2',
			'brand' => 'C',
			'country' => $this->params[0],
			'dateconfig' => date('Y-m-d'),
			'languageid' => $this->params[0],
			'taxincluded' => 'true',
			'responsetype' => 'Versions'
		);
		
		
		
		try {
			$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_MOTEUR_CONFIG', array());
			$response = $service->call('Select', $serviceParams);
		} catch(\Exception $e) {
			//echo $e->getMessage();
		}
		
		
		
		
		$aVehicules = \Citroen_View_Helper_Global::objectsIntoArray($response->SelectResponse->Versions);
		
		if(is_array($aVehicules['Version']) && sizeof($aVehicules['Version'])>0){
			foreach($aVehicules['Version'] as $aVehiculeVersion){
				$aInfosVehicules['COMBO'][$aVehiculeVersion['VehicleUse']['id'].'_'.$aVehiculeVersion['IdVersion']['id']] = "({$aVehiculeVersion['VehicleUse']['id']}) ({$aVehiculeVersion['IdVersion']['id']}) {$aVehiculeVersion['IdVersion']['label']}";																									
				// $aInfosVehicules['INFOS'][$aVehiculeVersion['IdVersion']['id']] = array('LABEL'=>$aVehiculeVersion['IdVersion']['label'],
																						// 'VEHICLEUSE_ID'=>$aVehiculeVersion['VehicleUse']['id'],
																						// 'MODEL_ID'=>$aVehiculeVersion['Model']['id'],
																						// 'BASE_PRICE'=>$aVehiculeVersion['Price']['basePrice'],
																						// 'NET_PRICE'=>$aVehiculeVersion['Price']['netPrice']);
			$aInfosVehicules['INFOS'][substr($aVehiculeVersion['IdVersion']['id'], 0, 6)] = array('LABEL'=>$aVehiculeVersion['IdVersion']['label'],
																						'VEHICLEUSE_ID'=>$aVehiculeVersion['VehicleUse']['id'],
																						'MODEL_ID'=>$aVehiculeVersion['Model']['id'],
																						'BASE_PRICE'=>$aVehiculeVersion['Price']['basePrice'],
																						'NET_PRICE'=>$aVehiculeVersion['Price']['netPrice']);
																						
			}
		}


        $this->value =  $aInfosVehicules;

      }
	  
	   public function getCodePays(){
        $oConnection = Pelican_Db::getInstance();
        $sqlCodePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(
            ":SITE_ID" => $_SESSION[APP]['SITE_ID']
        ));
        if(empty($sqlCodePays)){
            
            return false;
        }
        
        return $sqlCodePays; 
    }
}
?>