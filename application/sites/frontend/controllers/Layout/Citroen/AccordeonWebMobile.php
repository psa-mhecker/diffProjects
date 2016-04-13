<?php
class Layout_Citroen_AccordeonWebMobile_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
        $aData = $this->getParams();
        
        $aToggle =  Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["PAGE_ID"], 
            $aData['LANGUE_ID'], 
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'TOGGLE',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        if(!$this->isMobile() && is_array($aToggle) && !empty($aToggle)){
            $i = 0;
            foreach($aToggle as $multi){
                if(!empty($multi['MEDIA_ID'])){
                    $aToggle[$i]['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(
                            Pelican_Media::getMediaPath($multi['MEDIA_ID']),
                            Pelican::$config['MEDIA_FORMAT_ID']['FILTRE_CAR_SELECTOR']
                        );
                    $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                            $multi['MEDIA_ID']
                        ));
                    $aToggle[$i]['MEDIA_ALT'] = $mediaDetail['MEDIA_ALT'];
                }
                $i++;
            }
        }


        if($aData["callFromPromo"] == 1 && !$this->isMobile())
        {
                         $aData['ZONE_ORDER'] = $aData["PAGE_ID"]  ;
        }
       
        $this->assign('aData', $aData);
        $this->assign('NbToggle', count($aToggle));
        $this->assign('aToggle', $aToggle);
        
        $this->fetch();  
    }  
}  
?>