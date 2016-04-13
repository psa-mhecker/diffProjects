<?php

require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_Mosaique_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $aData = $this->getParams();

        $aMosaique =  Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'MOSAIQUE',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));

        if(is_array($aMosaique) && !empty($aMosaique)){
            $i = 0;
            foreach($aMosaique as $multi){
                $multi['PAGE_ZONE_MULTI_TEXT2'] = str_replace('<p>', '', $multi['PAGE_ZONE_MULTI_TEXT2']);
                $aMosaique[$i]['PAGE_ZONE_MULTI_TEXT2'] = str_replace('</p>', '', $multi['PAGE_ZONE_MULTI_TEXT2']);
                $i++;
            }
        }
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

        //Mentions légales
        if ($aData['ZONE_TITRE7'] != '') {
            $aMentionsLegales = Pelican_Cache::fetch("Frontend/Page", array(
                $aData['ZONE_TITRE7'],
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion()
            ));
        }
        if ($aData['MEDIA_ID4'] != '') {
            $sVisuelML = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_PETIT']);
        }

        $this->assign('aMentionsLegales', $aMentionsLegales);
        $this->assign('sVisuelML', $sVisuelML);
        $this->assign('aCTA', $aCTA);
        $this->assign('aData', $aData);
        $this->assign('aMosaique', $aMosaique);
        $this->fetch();

    }
}
?>
