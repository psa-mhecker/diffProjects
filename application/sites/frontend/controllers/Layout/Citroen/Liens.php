<?php  
class Layout_Citroen_Liens_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
        if($this->isMobile()){
            
            $aData = $this->getParams();
            $aMulti = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
                $aData["pid"], 
                $aData['LANGUE_ID'], 
                Pelican::getPreviewVersion(),
                $aData['ZONE_TEMPLATE_ID'],
                'LIENFORM',
                $aData['AREA_ID'],
                $aData['ZONE_ORDER']
            ));
            $this->assign('aData', $aData);  
            $this->assign('aMulti', $aMulti);
            $this->assign('NbLien', count($aMulti));
            $this->fetch();
        }         
    }  
}  
?>