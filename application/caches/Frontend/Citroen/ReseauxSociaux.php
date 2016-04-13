<?php

/**
 * Fichier de Pelican_Cache : Reseaux Sociaux
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_ReseauxSociaux extends Pelican_Cache {

    var $duration = HOUR;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */

    function getValue() {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
            select
                rs.*,
                m.MEDIA_PATH
            from #pref#_reseau_social rs
            inner join #pref#_media m
                on (rs.MEDIA_ID = m.MEDIA_ID)
            where SITE_ID = :SITE_ID
            and LANGUE_ID = :LANGUE_ID
            order by RESEAU_SOCIAL_ORDER asc
        ";
        $aTemp = $oConnection->queryTab($sSQL, $aBind);
        $aResults = array();
        $sFeed = '';
        $sXml = '';
        if ($aTemp) {
            foreach ($aTemp as $key => $temp) {
                if ($temp['RESEAU_SOCIAL_TYPE'] == 5) {
                    $nb_flux = $temp['RESEAU_SOCIAL_NB_FLUX'];
                    $aInstagram = array();
                    $sUrl = "http://iconosquare.com/feed/" . $temp['RESEAU_SOCIAL_ID_COMPTE'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $sUrl);
                    if (Pelican::$config ["TYPE_ENVIRONNEMENT"] != "dev" && Pelican::$config ["TYPE_ENVIRONNEMENT"] != "preprod") {
                        curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['PROXY']['URL'] . ":" . Pelican::$config['PROXY']['PORT'] . "");
                        curl_setopt($ch, CURLOPT_PROXYUSERPWD, "" . Pelican::$config['PROXY']['LOGIN'] . ":" . Pelican::$config['PROXY']['PWD'] . "");
                    }
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0');
                    $sFeed = curl_exec($ch);

                    if (strpos($sFeed, '<?xml') !== false) {
                        $sXml = @simplexml_load_string($sFeed);
                        for ($i = 0; $i < count($sXml->channel->item) && $i < $nb_flux; $i++) {
                            $uImg = "";
                            //on explose la description qui contient l'image
                            $aImg = explode("'", (string) $sXml->channel->item[$i]->description);
                            //on cherche là où commence la source de l'image et on la récupère	

                            foreach ($aImg as $iKey => $value) {
                                if (strpos($value, 'img') !== false) {
                                    $uImg = $aImg[$iKey + 1];
                                    break;
                                }
                            }
                            $aInstagram[] = array(
                                "title" => (string) $sXml->channel->item[$i]->title,
                                "image" => $this->convertInstaImg($uImg)
                            );
                        }

                        if (is_array($aInstagram) && count($aInstagram) > 0) {
                            $aTemp[$key]['FEED'] = $aInstagram;
                            $aResults[$temp['RESEAU_SOCIAL_ID']] = $aTemp[$key];
                        }
                    }
                }
                if( !isset($aResults[$temp['RESEAU_SOCIAL_ID']]) ){
                    $aResults[$temp['RESEAU_SOCIAL_ID']] = $temp;
                }
            }
        }
        $this->value = $aResults;
    }
    
    private function convertInstaImg($url_img){
        // paramètre
        $code_style = 's150x150';
        // convertir
        $img_parts = parse_url($url_img);
        $img_path = $img_parts['path'];
        $path_parts = explode('/', $img_path);
        $path_parts[2] = $code_style;
        $img_path = implode('/', $path_parts);
        $img_parts['path'] = $img_path;
        $url_img = http_build_url('', $img_parts);
        return $url_img;
    }
}