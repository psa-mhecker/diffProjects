<?php  
class Layout_Citroen_EligibiliteLinkMyCitroen_Controller extends Pelican_Controller_Front  
{  
  
    public function indexAction()  
    {  
    	$aData = $this->getParams();

        if (!empty($aData["MEDIA_ID"])) {
            // image
            $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID"]
            ));
            $mediaDetail["MEDIA_PATH"] = Pelican::$config['MEDIA_HTTP'].$mediaDetail["MEDIA_PATH"];
        } else {
            $mediaDetail["MEDIA_PATH"] = "http://www.citroen-ds-privilege.fr/img/firstvisit/img_help.jpg";
        }
        $this->assign('MediaUrl', $mediaDetail["MEDIA_PATH"]);
        $this->assign('aData', $aData);
        $this->fetch();  
    }  
    
    public function checkAction() {
    	
     	$aData = $this->getParams();
    	$retour['invalide_size']="";
    	$retour['message']="";
    	$VIN=$aData['VIN'];
    	 
    	 $aMsgConf = Pelican_Cache::fetch("Frontend/Page/ZoneInGabaritBlanc", array(
    	 
			$aData['pid'],
			$_SESSION[APP]['LANGUE_ID'],
    		Pelican::$config['AREA']['DYNAMIQUE'],
			Pelican::$config['ZONE']['ELIGIBILITE_LINK_MY_CITROEN'],
    		$aData['pversion'],	
				
		));

		$eligible= $aMsgConf[0]['ZONE_TEXTE3'];
		$nonEligible=$aMsgConf[0]['ZONE_TEXTE4']; 
		
		if(strlen($VIN) < 17 || !preg_match('/[a-z0-9]$/', $VIN)){
			$retour['invalide_size']='ko';	
		}
		
		else if(substr($VIN,0,4)=='VF70') {
			$retour['message']=$eligible;
		}

		//toutes Nouvelle C4 Picasso
		else if(substr($VIN,0,4)=='VF73'){
		
			$char10=substr($VIN,9,1);
			/* [0-9] ou [A-D] ==> le véhicule est déclaré incompatible
			*	Si 1er caractère du VIS = « E » Alors
			*	Si les 6 derniers caractères numériques du VIS sont strictement > à 598822 ==> le véhicule est considéré comme compatible
			*	Sinon ==> le véhicule est déclaré incompatible
			*/
			if(!preg_match('/[0-9]|[A-D]$/', substr($VIN,9,1))
					&& (((substr($VIN,9,1) == "E") && strcmp(substr($VIN,11,8) , '598822') > 0)
						|| preg_match('/[F-Z]$/', substr($VIN,9,1)))//Sinon ==> le véhicule est considéré comme compatible (véhicule dont le 1er caractère est autre que [0-9] / [A-E])
			){
				$retour['message']=$eligible;
			}
			else{
				$retour['message']=$nonEligible;
			} 
		}
		//C5/C5 Tourer
		else if(substr($VIN,0,4)=='VF7R'){
			if(!preg_match('/[0-9]|[A-E]$/', substr($VIN,9,1))
				&& (((substr($VIN,9,1) == "F") && strcmp(substr($VIN,11,8) , '507695') >= 0)
					|| preg_match('/[G-Z]$/', substr($VIN,9,1)))//Sinon ==> le véhicule est considéré comme compatible (véhicule dont le 1er caractère est autre que [0-9] / [A-E])
			){
				$retour['message'] = $eligible;
			}else{
				$retour['message']=$nonEligible;
			}
		}
		else{
			$retour['message']=$nonEligible;
		}
		
		
		echo (json_encode($retour));
	}
}  
		
		
		
		?> 