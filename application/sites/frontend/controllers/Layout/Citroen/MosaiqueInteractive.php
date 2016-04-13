<?php
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_MosaiqueInteractive_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $aData = $this->getParams();

        $aVisuelMosaique = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'VISUEL_MOSAIQUE',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        
        

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
       
        if(count($aVisuelMosaique) > 0)
        {
            $i = 0;           
            foreach($aVisuelMosaique as $Visuel){
                if(!empty($Visuel['MEDIA_ID'])){
                    $aVisuelMosaique[$i]['MEDIA_ID'] =  Pelican_Media::getMediaPath($Visuel['MEDIA_ID']);
                    $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                        $Visuel['MEDIA_ID']
                    ));
                    $aVisuelMosaique[$i]['MEDIA_ALT'] = $mediaDetail['MEDIA_ALT'];
                }
                if(!empty($Visuel['MEDIA_ID2'])){
                    $aVisuelMosaique[$i]['MEDIA_ID2'] = Pelican_Media::getMediaPath($Visuel['MEDIA_ID2']);
                    $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                        $Visuel['MEDIA_ID2']
                    ));
                    $aVisuelMosaique[$i]['MEDIA_ALT2'] = $mediaDetail['MEDIA_ALT'];
                }
                if(!empty($Visuel['MEDIA_ID3'])){
                    $aVisuelMosaique[$i]['MEDIA_ID3'] = Pelican_Media::getMediaPath($Visuel['MEDIA_ID3']);
                    $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                        $Visuel['MEDIA_ID3']
                    ));
                    $aVisuelMosaique[$i]['MEDIA_ALT3'] = $mediaDetail['MEDIA_ALT'];
                }
                if(!empty($Visuel['MEDIA_ID4'])){
                    $aVisuelMosaique[$i]['MEDIA_ID4'] = Pelican_Media::getMediaPath($Visuel['MEDIA_ID4']);
                    $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                        $Visuel['MEDIA_ID4']
                    ));
                    $aVisuelMosaique[$i]['MEDIA_ALT4'] = $mediaDetail['MEDIA_ALT'];
                }
                $i++;
            }
        }
		



        $this->assign('urlPopInMention',$pagePopUp["PAGE_CLEAR_URL"]);
        $this->assign('titlePopInMention', $pagePopUp["PAGE_TITLE"]);
        $this->assign('MEDIA_PATH4', $mediaDetailPush4["MEDIA_PATH"]);
        $this->assign('MEDIA_TITLE4', $mediaDetailPush4["MEDIA_TITLE"]);
        $this->assign('MEDIA_ALT4', $mediaDetailPush4["MEDIA_ALT"]);
        $this->assign('aVisuelMosaique', $aVisuelMosaique);
        if($this->isMobile()){
            $this->assign('NbVisuelMosaique', count($aVisuelMosaique));
        }
        $this->assign('aData', $aData);
        $this->fetch();
    }
}
?>
