<?php
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');
class Layout_Citroen_OffrePlus_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $aData = $this->getParams();
        
        if(strpos($aData['ZONE_TEXTE'],'<span>')){        
            $aData['ZONE_TEXTE'] = str_replace('<p><span>','<p><strong>', $aData['ZONE_TEXTE']);
            $aData['ZONE_TEXTE'] = str_replace('</span></p>','</strong></p>', $aData['ZONE_TEXTE']);
        }else{
            $aData['ZONE_TEXTE'] = str_replace('<p>','<p><strong>', $aData['ZONE_TEXTE']);
            $aData['ZONE_TEXTE'] = str_replace('</p>','</strong></p>', $aData['ZONE_TEXTE']);
        }
        $aMulti =  Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'SLIDEOFFREADDFORM',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        $aMulti = $this->patchImage($aMulti);

        $mediaDetailPush4 = Pelican_Cache::fetch("Media/Detail", array(
                $aData["MEDIA_ID4"]
            ));
        $aCTA =  Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'CTAFORM',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        if(is_array($aCTA)&& !empty($aCTA)){
            foreach($aCTA as $key=> $multi){
                if(isset($multi['OUTIL']) && !empty($multi['OUTIL'])){
                     $aData['CTA'] = $multi['OUTIL'];
                     
                     $aCTA[$key]['OUTIL'] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);
                }
            }
        }
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
        $this->assign('aCTA', $aCTA);
        $this->assign('aData', $aData);
        $this->assign('aMulti', $aMulti);

        $this->fetch();
    }

    public static function patchImage($aMulti)
    {
        /* Initialisation des variables */
        $i = 0;

        if ( is_array($aMulti) && !empty($aMulti) ){
            foreach($aMulti as $aOneMulti){
                $aMulti[$i]['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID']),Pelican::$config['MEDIA_FORMAT_ID']['OFFRE_PLUS_MULTI']);

                $i++;
            }
        }

        return $aMulti;
    }
}
?>
