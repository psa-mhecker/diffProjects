<?php


class Frontend_Citroen_NavigationSession extends Pelican_Cache
{
    public $duration = DAY;

    public $deprecated = false;

    public $cacheType = "Cache/Session";

    public static $storage = 'Session';

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $isMobile = isset($this->params[1]) ? $this->params[1] : null;

        $navigationSite = parent::fetch("Frontend/Citroen/Navigation", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            'PAGE_DISPLAY_NAV',
            7,
            $isMobile,
        ));

        $productMedia = Pelican_Cache::fetch("Frontend/Citroen/Perso/ProductMedia", array(
            $_SESSION[APP]['SITE_ID'],
        ));
        $produitPrefere = $this->params[0];

        if (is_array($navigationSite) && count($navigationSite)>0) {
            foreach ($navigationSite as $i => $nav) {
                if ($nav['n1']['expand'] == '1') {
                    if ($nav['n2']) {
                        foreach ($nav['n2'] as $k => $n2) {
                            $aMultiGeneral = array('PUSH_OUTILS_MAJEUR', 'PUSH_OUTILS_MINEUR', 'PUSH_CONTENU_ANNEXE');
                            foreach ($aMultiGeneral as $multiGeneral) {
                                $results = Citroen_Cache::fetchProfiling($n2['perso'], "Frontend/Citroen/MultiNavigation", array(
                                    $n2['id'],
                                    $n2['pv'],
                                    $_SESSION[APP]['LANGUE_ID'],
                                    $multiGeneral,
                                ));

                                if (is_array($results) && count($results)>0) {
                                    foreach ($results as $key => $result) {
                                        $results[$key]['MEDIA_PATH'] = ($result['MEDIA_ID_GENERIQUE'] && !empty($produitPrefere) && !empty($productMedia[$produitPrefere]['EXPAND_VEHICULE'])) ? Citroen_Media::getFileNameMediaFormat($productMedia[$produitPrefere]['EXPAND_VEHICULE'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_PUSH']) : $result['MEDIA_PATH'];
                                    }
                                }

                                $navigationSite[$i]['n2'][$k][$multiGeneral] = $results;
                            }
                        }
                    }
                } else {
                    $results = Citroen_Cache::fetchProfiling($nav['n1']['perso'], "Frontend/Citroen/MultiNavigation", array(
                        $nav['n1']['id'],
                        $nav['n1']['pv'],
                        $_SESSION[APP]['LANGUE_ID'],
                        'PUSH',
                    ));
                    if (is_array($results) && count($results)>0) {
                        foreach ($results as $key => $result) {
                            $results[$key]['MEDIA_PATH'] = ($result['MEDIA_ID_GENERIQUE'] && !empty($produitPrefere) && !empty($productMedia[$produitPrefere]['EXPAND_STANDARD'])) ? Citroen_Media::getFileNameMediaFormat($productMedia[$produitPrefere]['EXPAND_STANDARD'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_PUSH']) : $result['MEDIA_PATH'];
                        }
                    }
                    $navigationSite[$i]['n1']['PUSH'] = $results;
                }
            }
        }
        $this->value = $navigationSite;
    }
}
