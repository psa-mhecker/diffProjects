<?php
class Layout_Citroen_ContractServices_Controller extends Pelican_Controller_Front  
{   
    public function indexAction()  
    {  
        $aData = $this->getParams();
        
        $aContract =  Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"], 
            $aData['LANGUE_ID'], 
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'CONTRACT_SERVICE',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        
        $count = count($aContract);
        $service = '';
        
        if(!empty($aContract) && is_array($aContract)){
            $i = 0;
            $aContractTemp = array();
            $aContractTemp2 = array();
            $aTemp = array();
            foreach($aContract as $Visuel){
                $service = '';
                $aOneContract = $Visuel;
                
                if(!empty($Visuel['MEDIA_ID']))
                    $aOneContract['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneContract['MEDIA_ID']),Pelican::$config['MEDIA_FORMAT_ID']['WEB_CONTRAT_SERVICE']);
                
                for($j = $i + 1; $j <= $count; $j++){
                    $service .= 'services'.$j.' ';
                }
                $aOneContract['SERVICE'] = $service;
                
                if($this->isMobile()){
                    $aTemp[] = $aOneContract['PAGE_ZONE_MULTI_TEXT2'];              
                    $aContractTemp[$i] = $aTemp;
                    $aContractTemp2[$i] = $aOneContract;
                }
                else{              
                    $aContractTemp[$i] = $aOneContract;
                }
                
                $i++;
            }

            $aContract = $aContractTemp;
            if($this->isMobile()){
                $aContractMobi = $aContractTemp2;
            }  
        }
        
        $this->assign('aData', $aData);
        $this->assign('aContract', $aContract);
        if($this->isMobile()){
            $this->assign('aContractMobi', $aContractMobi);
        }
                
        $this->fetch();  
    }  
}
?>
