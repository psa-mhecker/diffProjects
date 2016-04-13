<?php  
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');
class Layout_Citroen_AutreResaux_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
        $aData = $this->getParams(); 
        
        $reseauxSociauxSelected = explode('|', $aData['ZONE_PARAMETERS']);
        
        $this->assign("reseauxSociauxSelected", $reseauxSociauxSelected);
        
        $reseauxSociaux = Pelican_Cache::fetch("Frontend/Citroen/ReseauxSociaux", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        
        $aTypesRs = array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX']);      
        if(!empty($reseauxSociaux)){
            foreach($reseauxSociaux as $key=>$rs){
                $reseauxSociaux[$key]['RS'] = strtolower($aTypesRs[$rs['RESEAU_SOCIAL_TYPE']]);
              }
        }
			
        $this->assign('reseauxSociaux', $reseauxSociaux);
        $this->assign('aData', $aData);  
        
        $this->fetch();
    }  
} 
?>
