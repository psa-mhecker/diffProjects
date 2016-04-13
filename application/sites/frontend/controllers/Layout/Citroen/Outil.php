<?php


require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_Outil_Controller extends Pelican_Controller_Front {

private static $showOneTime = false;
    public function indexAction() {

      
        if(self::$showOneTime) return;

         $aData = $this->getParams();
 
     
		$mode="";
		
		if($aData['TEMPLATE_PAGE_ID']==Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] || $aData['TEMPLATE_PAGE_ID']==Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']){
			
			if($aData['AREA_ID']==Pelican::$config['AREA']['CORPS_PAGE'] ){
				$mode="vertical";
                if(!$this->isMobile()){
                    self::$showOneTime = true;
                }
			}
			else{
				$mode="to_hide";
			}
			
		}
        $this->assign('display', '1');

        // cache qui remonte les outils mobile ou web
        $aOutil = Pelican_Cache::fetch("Frontend/Citroen/VehiculeOutil", array(
                    $aData['SITE_ID'],
                    $aData['LANGUE_ID'],
                    ($this->isMobile()) ? $aData['ZONE_TOOL2'] : $aData['ZONE_TOOL'],
                    ($this->isMobile()) ? "MOBILE" : "WEB"
        ));
        $aTrancheFromContact = Pelican::$config['TRANCHE_FORMULAIRE_CONTACT'];
        
        // Définition du code couleur à utiliser
        $codeCouleur = Frontoffice_Zone_Helper::getCodeCouleurOutil($aData['ZONE_TITRE19'], $aData['ZONE_TITRE3'], $aData['ZONE_TITRE4']);
        $this->assign('codeCouleur', $codeCouleur);
        
        // Définition picto pour chaque outil
        Frontoffice_Zone_Helper::addPictoOutil($aData['ZONE_TITRE19'], $aOutil,$mode);
        if (is_array($aOutil) && !empty($aOutil)) {
				
                foreach ($aOutil as $key => $OneOutil){		
                    if(!$this->isMobile()){
                        if(!empty( $aData['ZONE_SKIN'])){
                            $OneOutil['MORE_URL_PARAMETERS'] = array(
                                'page_skin ='. $aData['ZONE_SKIN']
                            );
                        }
                    }
                    
                    $aData['CTA'] = $OneOutil;
					if($this->isMobile()){
						 $aData['CTA']['ADD_CSS'] ='buttonFindShop';
					}else{
						$aData['CTA']['ADD_CSS'] ='activeRoll';
					}
                 
                    $aOutil[$key] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);
                }
				            
        }
		


        $IsHome = 0;
        if($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['HOME']) {
            $IsHome = 1;
        }
       $this->assign('IsHome', $IsHome);
                
		$this->assign('aDataOutils', $aData);
        $this->assign('mode', $mode);
        $this->assign('aOutil', $aOutil);
        $this->assign('NbOutil', count($aOutil));

        $this->fetch();
    }

}
