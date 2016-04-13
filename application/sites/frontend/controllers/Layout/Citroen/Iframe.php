<?php
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');
class Layout_Citroen_Iframe_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aData = $this->getParams();


        $this->assign('aData', $aData);

        if($aData['ZONE_TEXTE3'] != ""){
            $aId = explode("\r\n", $this->getParam('ZONE_TEXTE3'));
            if (! is_array($aId)){
                $aId = array();
            }
            $id = implode(',', $aId);
        }
        $this->assign('dataHide', $id);
        
        if($aData['ZONE_TEXTE6'] != ""){
            $aId = explode("\r\n", $this->getParam('ZONE_TEXTE6'));
            if (! is_array($aId)){
                $aId = array();
            }
            $id = implode(',', $aId);
        }
        $this->assign('mobileDataHide', $id);

        $mediaDetailPush4 = Pelican_Cache::fetch("Media/Detail", array(
                $aData["MEDIA_ID4"]
            ));

        if(isset($aData["ZONE_TITRE7"]) && $aData["ZONE_TITRE7"] != ""){
            $pagePopUp = Pelican_Cache::fetch("Frontend/Page", array(
                $aData["ZONE_TITRE7"],
                $aData['SITE_ID'],
                $aData['LANGUE_ID']
            ));
        }
        $this->assign('urlPopInMention', $pagePopUp["PAGE_CLEAR_URL"]);
        $this->assign('titlePopInMention', $pagePopUp["PAGE_TITLE"]);
        $this->assign('MEDIA_PATH4', $mediaDetailPush4["MEDIA_PATH"]);
        $this->assign('MEDIA_TITLE4', $mediaDetailPush4["MEDIA_TITLE"]);
        $this->assign('MEDIA_ALT4', $mediaDetailPush4["MEDIA_ALT"]);

        $aCta = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'CTAFORM',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        if(is_array($aCta)&& !empty($aCta)){
            foreach($aCta as $key=> $multi){
                if(isset($multi['OUTIL']) && !empty($multi['OUTIL'])){
                     $aData['CTA'] = $multi['OUTIL'];
                     
                     $aCta[$key]['OUTIL'] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);
                }
            }
        }
		 $this->assign('aCta', $aCta);
		 
		//Sharer
		if(isset($aData['ZONE_TITRE20']) && !empty($aData['ZONE_TITRE20'])){
			$sMediaSharer = '';
			if(!empty($aData["MEDIA_PATH2"])){
				$sMediaSharer = '?content_media='.Pelican::$config['MEDIA_HTTP'].$aData["MEDIA_PATH2"];
			}
			if(!empty($aData["ZONE_TITRE3"])){
				
				if(!empty($aData["MEDIA_PATH2"])){
					$sMediaSharer.= '&content_title='.$aData["ZONE_TITRE3"];
				}else{
					$sMediaSharer.= '?content_title='.$aData["ZONE_TITRE3"];
				}
			}				
		
			$sIdHtml = '#'.$aData['ID_HTML'];		
			$sSharer = Backoffice_Share_Helper::getSharer($aData['ZONE_TITRE20'],$aData['SITE_ID'], $aData['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aData,'ANCHOR'=>$sIdHtml,'MEDIA_IFRAME_SHARE'=>$sMediaSharer,'content_title'=>$aData["ZONE_TITRE3"]));
			$this->assign("sSharerIframe", $sSharer);
		}
		
        $this->fetch();
    }
}