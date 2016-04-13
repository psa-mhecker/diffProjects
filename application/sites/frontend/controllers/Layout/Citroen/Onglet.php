<?php

require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_Onglet_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aParams = $this->getParams();
		

				
			if($aParams['ZONE_MOBILE']!=0){
				if(is_array(Pelican::$config['JAVASCRIPT_FOOTER']['MOBILE'])){
					array_push(Pelican::$config['JAVASCRIPT_FOOTER']['MOBILE'],
							Pelican::$config['DESIGN_HTTP']."/assets/js/common/Tranches/onglet-mobile.js"
					);
				}	
			}

		
        $this->assign("aParams", $aParams);
        
        $aOnglets = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aParams['PAGE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aParams['ZONE_TEMPLATE_ID'],
            'ONGLET',
            $aParams['AREA_ID'],
            $aParams['ZONE_ORDER']
        ));
        
        // Vérification des onglets après filtrage web/mobile
        $idOngletTab = 0;
        $aPageZone = Pelican_Cache::fetch('Frontend/Page/Zone',array(
            $aParams['pid'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion()
        ));
        $flag = false;            
        foreach($aPageZone['zones'] as $zone=>$pid){
            foreach($pid as $tranche){
                foreach($tranche as $info){
                    // Si le filtrage est activé
                    if($flag){
                        // Si on est en mobile et que la tranche s'affiche en web uniquement => on la supprime
                        if( $this->isMobile() && $info['ZONE_WEB'] == 1 && $info['ZONE_MOBILE'] == 0){
                            unset($aOnglets[$idOngletTab]);
                            ksort($aOnglets);
                            $idOngletTab++;
                        }
                        // Si on est en web et que la tranche s'affiche en mobile uniquement => on la supprime
                        else if( !$this->isMobile() && $info['ZONE_WEB'] == 0 && $info['ZONE_MOBILE'] == 1){
                            unset($aOnglets[$idOngletTab]);
                            ksort($aOnglets);
                            $idOngletTab++;
                        }
                        else{
                            $idOngletTab++;
                        }
                    }
                    
                    // Si la tranche est de type onglet, on active le filtrage (pour les tranches suivantes)
                    if($info['ZONE_ID'] == Pelican::$config['ZONE']['ONGLET']){
                       $flag = true;
                    }
                }
            }
        }
		
        
        // Si on a aucun onglet à afficher, on n'affiche pas la tranche onglet (CPW-2508)
        if(empty($aOnglets)){
            return;
        }
        
        $this->assign("aOnglets", $aOnglets);

        $this->fetch();
    }

}