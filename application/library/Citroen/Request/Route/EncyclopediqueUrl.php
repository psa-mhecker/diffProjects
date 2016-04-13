<?php
include_once ('Pelican/Request/Route/Abstract.php');

class Citroen_Request_Route_EncyclopediqueUrl extends Pelican_Request_Route_Abstract
{

    public function eligible()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $encyclopediqueUrls = Pelican_Cache::fetch('Frontend/Citroen/UrlEncyclopedique');
        if(!is_array($encyclopediqueUrls)){
            
            return false;
        }        
        foreach($encyclopediqueUrls as $encyclopediqueUrl){
            if($encyclopediqueUrl['URL_ENCYCLOPEDIQUE_SOURCE'] == $url){
                $this->uri = $encyclopediqueUrl['URL_ENCYCLOPEDIQUE_DESTINATION'];
                return true;
            }
            if (strpos($encyclopediqueUrl['URL_ENCYCLOPEDIQUE_SOURCE'], '[*]') !== false){
                $patternUrl = str_replace('[*]', '', $encyclopediqueUrl['URL_ENCYCLOPEDIQUE_SOURCE']);
            }
            if(!empty($patternUrl) && strpos($url, $patternUrl) !== false){
                $this->uri = $encyclopediqueUrl['URL_ENCYCLOPEDIQUE_DESTINATION'];

                return true;
            }
        }
        
        return false;
    }

    public function match()
    {     
        header('location:' . $this->uri , true, 301);
        die();
    }
}
