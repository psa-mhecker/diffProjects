<?php
/**
 * Fichier de Pelican_Caches_Citroen : GDG
 *
 * La source de ce fichier de cache est le WebService GDG. 
 *
 * Ce WebService permet de remonter les données du GDG Brochure et carpicker
 *
 * @package Cache
 * @subpackage Pelican
 * @author  David Moate <david.moate@businessdecision.com>
 * @since   15/02/2016
 */

use Citroen\GDG;

class Citroen_GDG extends Pelican_Cache {

    public $duration = HOUR;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
        /* Initialisation des variables */
        $type       = (string)$this->params[0];
        $languages  = (string)$this->params[1];
        $countries  = (string)$this->params[2];
        $brands     = (string)$this->params[3];
        $ranges     = (string)$this->params[4];
        $_format    = (string)$this->params[5];
        $contexts   = (string)$this->params[6];
      	$slcdv      = (string)$this->params[7];
        
        if($type == Pelican::$config['GDG']['CAR_PICKER']){
            $result = GDG::getCarPicker($languages, $countries, $brands, $ranges, $_format, $contexts);
        }else{
            $result = GDG::getBrochure($languages, $countries, $brands, $ranges, $_format); 
        }
		//debug( $result,' $result');

		$sLabel='';
		
		if(is_array($result) && sizeof($result)>0 && $slcdv!=''){
				foreach($result as $iKey=>$aValue){
						if(is_array($aValue->Bodies->Body) && sizeof($aValue->Bodies->Body)>0){
						foreach($aValue->Bodies->Body as $iKey=>$aValueTab){
							if(strlen($slcdv)==16){//Si LCDV_entree sur 16 caractères
										if(is_object($aValueTab)){
											switch($aValueTab->LCDVCustom){
										
													case substr($slcdv, 0,8):  //Tentative de matching avec les LCDVCustom du GDG sur 8 caractères
													$sLabel = $aValueTab->Label;
													break;
													case substr($slcdv, 0,7)://Sinon tentative de matching avec les LCDVCustom du GDG sur 7 caractères
													$sLabel = $aValueTab->Label;
													break;
													case substr($slcdv, 0,6)://Sinon tentative de matching avec les LCDVCustom du GDG sur 6 caractères
													$sLabel = $aValueTab->Label;
													break;
											}		
										}	
							}elseif(strlen($slcdv)==6){//Si LCDV_entree sur 6 caractères
										if(is_object($aValueTab)){
											if($aValueTab->LCDVCustom == $slcdv){//Tentative de matching avec les LCDVCustom du GDG sur 6 caractères 
												$sLabel = $aValueTab->Label;
												break;
											}
										}
									
							}
						}							
					}
				}
			
		}
        
        $this->value = $sLabel;
    }
}

