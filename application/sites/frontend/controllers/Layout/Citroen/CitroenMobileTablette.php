<?php
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');
class Layout_Citroen_CitroenMobileTablette_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
        $aData = $this->getParams();
        
        $aPlatformMixte = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"], 
            $aData['LANGUE_ID'], 
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'APPLI',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        
        $aPlatformMixte = $this->patchImage($aPlatformMixte);
        
        $aPlatformWeb = $this->getPlatformWeb($aPlatformMixte);
        
        $aPlatformAppli = $this->getPlatformAppli($aPlatformMixte);
        

     
        $this->assign('aPlatformWeb', $aPlatformWeb);
        $this->assign('aPlatformAppli', $aPlatformAppli);
        
        $this->assign('aData', $aData);  
        $this->fetch();  
    } 
    
    
    public static function getPlatformWeb($aPlatformMixte)
    {
        $aWeb = array(); $i = 0;
        if (is_array($aPlatformMixte) && !empty($aPlatformMixte)){
            foreach($aPlatformMixte as $aOnePlatform){
                    if(!empty($aOnePlatform['PAGE_ZONE_MULTI_LABEL']) && !empty($aOnePlatform['PAGE_ZONE_MULTI_URL']) && !empty($aOnePlatform['PAGE_ZONE_MULTI_VALUE'])){                    
                     
                    $aWeb[$i]['TITRE'] = $aOnePlatform['PAGE_ZONE_MULTI_TITRE'];
                    $aWeb[$i]['MEDIA'] = $aOnePlatform['MEDIA_ID'];
                    $aWeb[$i]['TEXTE'] = $aOnePlatform['PAGE_ZONE_MULTI_TEXT'];
                
                    $aWeb[$i]['Lien'] = $aOnePlatform['PAGE_ZONE_MULTI_LABEL'];
                    $aWeb[$i]['WEB'] = $aOnePlatform['PAGE_ZONE_MULTI_URL'];
                    $aWeb[$i]['MOBILE'] = $aOnePlatform['PAGE_ZONE_MULTI_URL2'];
                    $aWeb[$i]['TARGET'] = $aOnePlatform['PAGE_ZONE_MULTI_VALUE'];
                    
                    $i++;
                }                
            }
        }
        return $aWeb;
    }
    
    public static function getPlatformAppli($aPlatformMixte)
    {
        $aAppli = array(); $i = 0;
        if (is_array($aPlatformMixte) && !empty($aPlatformMixte)){
            foreach($aPlatformMixte as $aOnePlatform){               
                 
                if(!empty($aOnePlatform['PAGE_ZONE_MULTI_LABEL2']) && (!empty($aOnePlatform['PAGE_ZONE_MULTI_URL3']) || !empty($aOnePlatform['PAGE_ZONE_MULTI_URL4']))){
                  
                    $aAppli[$i]['TITRE'] = $aOnePlatform['PAGE_ZONE_MULTI_TITRE'];
                    $aAppli[$i]['MEDIA'] = $aOnePlatform['MEDIA_ID'];
                    $aAppli[$i]['TEXTE'] = $aOnePlatform['PAGE_ZONE_MULTI_TEXT'];

                     
                    $aAppli[$i]['PLATFORM_1'] = $aOnePlatform['PAGE_ZONE_MULTI_LABEL2'];
                    $aAppli[$i]['MEDIA_1'] = $aOnePlatform['MEDIA_ID2'];
                    $aAppli[$i]['WEB_1'] = $aOnePlatform['PAGE_ZONE_MULTI_URL3'];
                    $aAppli[$i]['MOBILE_1'] = $aOnePlatform['PAGE_ZONE_MULTI_URL4'];
                    
                    if(!empty($aOnePlatform['PAGE_ZONE_MULTI_LABEL3']) && (!empty($aOnePlatform['PAGE_ZONE_MULTI_URL5']) || !empty($aOnePlatform['PAGE_ZONE_MULTI_URL6']))){
                        
                        $aAppli[$i]['PLATFORM_2'] = $aOnePlatform['PAGE_ZONE_MULTI_LABEL3'];
                        $aAppli[$i]['MEDIA_2'] = $aOnePlatform['MEDIA_ID3'];
                        $aAppli[$i]['WEB_2'] = $aOnePlatform['PAGE_ZONE_MULTI_URL5'];
                        $aAppli[$i]['MOBILE_2'] = $aOnePlatform['PAGE_ZONE_MULTI_URL6'];
                        
                        if(!empty($aOnePlatform['PAGE_ZONE_MULTI_LABEL4']) && (!empty($aOnePlatform['PAGE_ZONE_MULTI_URL7']) || !empty($aOnePlatform['PAGE_ZONE_MULTI_URL8']))){
                        
                            $aAppli[$i]['PLATFORM_3'] = $aOnePlatform['PAGE_ZONE_MULTI_LABEL4'];
                            $aAppli[$i]['MEDIA_3'] = $aOnePlatform['MEDIA_ID4'];
                            $aAppli[$i]['WEB_3'] = $aOnePlatform['PAGE_ZONE_MULTI_URL7'];
                            $aAppli[$i]['MOBILE_3'] = $aOnePlatform['PAGE_ZONE_MULTI_URL8'];

                        }
                        
                    }
                    
                    $i++;
                } 
            }
        }
        return $aAppli;
    }
    
    public static function patchImage($aMulti)
    {
        /* Initialisation des variables */
        $i = 0;
        if ( is_array($aMulti) && !empty($aMulti)){
            foreach($aMulti as $aOneMulti){
                
                if(!empty($aOneMulti['MEDIA_ID'])){
                    $aMulti[$i]['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID']),Pelican::$config['MEDIA_FORMAT_ID']['PETIT_CARRE']);
                }
                if(!empty($aOneMulti['MEDIA_ID2'])){
                    $aMulti[$i]['MEDIA_ID2'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID2']),Pelican::$config['MEDIA_FORMAT_ID']['APP_MOBILE_STORE']);
                }
                if(!empty($aOneMulti['MEDIA_ID3'])){
                    $aMulti[$i]['MEDIA_ID3'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID3']),Pelican::$config['MEDIA_FORMAT_ID']['APP_MOBILE_STORE']);
                }
                if(!empty($aOneMulti['MEDIA_ID4'])){
                    $aMulti[$i]['MEDIA_ID4'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID4']),Pelican::$config['MEDIA_FORMAT_ID']['APP_MOBILE_STORE']);
                }
                
                $i++;
            }
        }
        
        return $aMulti;
    }
} 
?>
