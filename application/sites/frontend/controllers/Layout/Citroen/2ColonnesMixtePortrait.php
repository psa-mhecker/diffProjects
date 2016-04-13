<?php

require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_2ColonnesMixtePortrait_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();

        if ($aData["ZONE_WEB"] == 1 || $aData["ZONE_MOBILE"] == 1) {
            $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID"]
            ));
            $mediaDetail2 = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID2"]
            ));
            
            if(isset($aData['PAGE_VEHICULE'])){
                $vehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array(
                    $aData['PAGE_VEHICULE'],
                    $aData['SITE_ID'],
                    $aData['LANGUE_ID']
                ));
                $vehicule_label = $vehicule['VEHICULE_LABEL'];
            }
            
            //multi CTA
            $multiValues = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
                        $aData['PAGE_ID'],
                        $aData['LANGUE_ID'],
                        Pelican::getPreviewVersion(),
                        $aData['ZONE_TEMPLATE_ID'],
                        'CTAFORM',
                        $aData['AREA_ID'],
                        $aData['ZONE_ORDER']
            ));
            if(is_array($multiValues)&& !empty($multiValues)){
                foreach($multiValues as $key=> $multi){
                    if(isset($multi['OUTIL']) && !empty($multi['OUTIL'])){
                         $aData['CTA'] = $multi['OUTIL'];
                        //$aData['CTA']['ADD_CTT'] = 'buttonLeadPicto';
                        $multiValues[$key]['OUTIL'] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);
                    }
                }
            }



            $this->assign('MEDIA_PATH', Pelican::$config['MEDIA_HTTP'] . $mediaDetail["MEDIA_PATH"]);
            $this->assign('MEDIA_TITLE', $mediaDetail["MEDIA_TITLE"]);
            $this->assign('MEDIA_ALT', $mediaDetail["MEDIA_ALT"]);
            $this->assign('MEDIA_PATH2', Pelican::$config['MEDIA_HTTP'] . $mediaDetail2["MEDIA_PATH"]);
            $this->assign('MEDIA_ALT2', $mediaDetail2["MEDIA_ALT"]);
            $this->assign('aCta', $multiValues);
            $this->assign('aData', $aData);
        }

        $this->fetch();
    }

    private function replaceGTM($html, $vehicule_label = '') {
        $dom = new domDocument;
        $dom->loadHTML($html);
        $tags_a = $dom->getElementsByTagName('a');
        foreach ($tags_a as $a) {
            $href = trim($a->getAttribute('href'));
            $data_gtm = $a->getAttribute('data-gtm');
            $gtm = explode('|', $data_gtm);
            $gtm[1] = 'Showroom:TranchePortrait';
            if (strpos($href, 'http://configurer') === 0) { // si le lien vers configurateur
                $action = 'Configurator::' . $vehicule_label;
            } else {
                $action = 'Redirection';
            }
            $gtm[2] = $action;
            $a->setAttribute('data-gtm', implode('|', $gtm));
        }
        return $dom->saveHTML();
    }

}
